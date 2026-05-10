<?php
// app/Http/Controllers/BookingController.php
namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Province;
use App\Models\Truck;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\TransportationRate;
use App\Models\EmptyReturnRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('bookings.index', compact('bookings'));
    }

    public function createImport()
    {
        $ports = Port::where('type', 'origin')->get();
        $provinces = Province::orderBy('name_en')->get();
        $trucks = Truck::all();
        $depots = EmptyReturnRate::distinct('depot_name')->pluck('depot_name');
        
        return view('bookings.create-import', compact('ports', 'provinces', 'trucks', 'depots'));
    }

    public function createExport()
    {
        $ports = Port::where('type', 'origin')->get();
        $destinationPorts = Port::whereIn('name_en', ['Sihanoukville Autonomous Port', 'Phnom Penh Autonomous Port'])->get();
        $trucks = Truck::all();
        
        return view('bookings.create-export', compact('ports', 'destinationPorts', 'trucks'));
    }

    public function calculatePrice(Request $request)
    {
        $request->validate([
            'origin_port_id' => 'required|exists:ports,id',
            'destination_province_id' => 'required|exists:provinces,id',
            'container_size' => 'required|in:45HC,40HC,40DC,20GP',
            'weight' => 'required|numeric|min:1',
            'type' => 'required|in:import,export',
            'empty_return_depot' => 'nullable|string',
        ]);

        // Get transportation rate
        $rate = TransportationRate::where('origin_port_id', $request->origin_port_id)
                                  ->where('destination_province_id', $request->destination_province_id)
                                  ->where('container_size', $request->container_size)
                                  ->where('type', $request->type)
                                  ->first();

        if (!$rate) {
            return response()->json([
                'success' => false,
                'message' => 'Transportation rate not found for this route'
            ], 404);
        }

        $transportFee = $rate->rate;

        // Calculate overweight charge
        $overweightCharge = 0;
        if ($request->weight > 23) {
            $extraTons = $request->weight - 23;
            $overweightCharge = $extraTons * 30;
        }

        // Calculate empty return fee
        $emptyReturnFee = 0;
        if ($request->empty_return_depot) {
            $emptyReturnFee = EmptyReturnRate::getRate($request->empty_return_depot, $request->container_size);
        }

        $totalAmount = $transportFee + $overweightCharge + $emptyReturnFee;
        $depositAmount = $totalAmount * 0.5;

        return response()->json([
            'success' => true,
            'transport_fee' => $transportFee,
            'overweight_charge' => $overweightCharge,
            'empty_return_fee' => $emptyReturnFee,
            'total_amount' => $totalAmount,
            'deposit_amount' => $depositAmount
        ]);
    }

    public function storeImport(Request $request)
    {
        $request->validate([
            'origin_port_id' => 'required|exists:ports,id',
            'destination_province_id' => 'required|exists:provinces,id',
            'container_size' => 'required|in:45HC,40HC,40DC,20GP',
            'weight' => 'required|numeric|min:1',
            'pickup_date' => 'required|date|after_or_equal:today',
            'delivery_date' => 'required|date|after:pickup_date',
            'container_release_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'empty_return_depot' => 'required|string',
            'truck_id' => 'required|exists:trucks,id',
            'transport_fee' => 'required|numeric',
            'overweight_charge' => 'required|numeric',
            'empty_return_fee' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'deposit_amount' => 'required|numeric',
        ]);

        // Upload file
        $filePath = null;
        if ($request->hasFile('container_release_file')) {
            $filePath = $request->file('container_release_file')->store('container-releases', 'public');
        }

        // Check if truck is available
        $truck = Truck::find($request->truck_id);
        if ($truck->status !== 'available') {
            return back()->with('error', 'Selected truck is not available at the moment.');
        }

        // Create booking
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'type' => 'import',
            'user_id' => Auth::id(),
            'origin_port_id' => $request->origin_port_id,
            'destination_province_id' => $request->destination_province_id,
            'container_size' => $request->container_size,
            'weight' => $request->weight,
            'overweight_charge' => $request->overweight_charge,
            'pickup_date' => $request->pickup_date,
            'delivery_date' => $request->delivery_date,
            'container_release_file' => $filePath,
            'empty_return_depot' => $request->empty_return_depot,
            'empty_return_fee' => $request->empty_return_fee,
            'truck_id' => $request->truck_id,
            'transport_fee' => $request->transport_fee,
            'total_amount' => $request->total_amount,
            'deposit_amount' => $request->deposit_amount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Update truck status to delivering
        $truck->update(['status' => 'delivering']);

        // Redirect to payment page
        return redirect()->route('payment.show', ['booking' => $booking->id]);
    }

    public function storeExport(Request $request)
    {
        $request->validate([
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
            'container_size' => 'required|in:45HC,40HC,40DC,20GP',
            'cargo_loading_location' => 'required|string',
            'factory_phone' => 'required|string',
            'weight' => 'required|numeric|min:1',
            'pickup_date' => 'required|date|after_or_equal:today',
            'factory_loading_date' => 'required|date|after_or_equal:pickup_date',
            'delivery_date' => 'required|date|after:factory_loading_date',
            'document_handler_phone' => 'required|string',
            'pickup_release_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'truck_id' => 'required|exists:trucks,id',
            'transport_fee' => 'required|numeric',
            'overweight_charge' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'deposit_amount' => 'required|numeric',
        ]);

        // Upload file
        $filePath = null;
        if ($request->hasFile('pickup_release_file')) {
            $filePath = $request->file('pickup_release_file')->store('pickup-releases', 'public');
        }

        // Check if truck is available
        $truck = Truck::find($request->truck_id);
        if ($truck->status !== 'available') {
            return back()->with('error', 'Selected truck is not available at the moment.');
        }

        // Create booking
        $booking = Booking::create([
            'booking_number' => Booking::generateBookingNumber(),
            'type' => 'export',
            'user_id' => Auth::id(),
            'origin_port_id' => $request->origin_port_id,
            'destination_port_id' => $request->destination_port_id,
            'container_size' => $request->container_size,
            'cargo_loading_location' => $request->cargo_loading_location,
            'factory_phone' => $request->factory_phone,
            'weight' => $request->weight,
            'overweight_charge' => $request->overweight_charge,
            'pickup_date' => $request->pickup_date,
            'factory_loading_date' => $request->factory_loading_date,
            'delivery_date' => $request->delivery_date,
            'document_handler_phone' => $request->document_handler_phone,
            'pickup_release_file' => $filePath,
            'truck_id' => $request->truck_id,
            'transport_fee' => $request->transport_fee,
            'total_amount' => $request->total_amount,
            'deposit_amount' => $request->deposit_amount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Update truck status to delivering
        $truck->update(['status' => 'delivering']);

        // Redirect to payment page
        return redirect()->route('payment.show', ['booking' => $booking->id]);
    }

    public function show(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully.');
        }
        return back()->with('error', 'Cannot cancel this booking.');
    }

    public function storeTruckBooking(Request $request)
    {
        $request->validate([
            'full_name'       => 'required|string|max:100',
            'phone'           => 'required|string|max:20',
            'email'           => 'nullable|email',
            'booking_type'    => 'required|in:import,export',
            'pickup_location' => 'required|string|max:200',
            'dropoff_location'=> 'required|string|max:200',
            'pick_up_date'    => 'required|date|after_or_equal:today',
            'drop_off_date'   => 'required|date|after_or_equal:pick_up_date',
            'cargo_weight'    => 'required|numeric|min:1',
        ], [
            'full_name.required'        => 'សូមបញ្ចូលឈ្មោះពេញ។',
            'phone.required'            => 'សូមបញ្ចូលលេខទូរស័ព្ទ។',
            'booking_type.required'     => 'សូមជ្រើសរើសប្រភេទការដឹកជញ្ជូន។',
            'pickup_location.required'  => 'សូមបញ្ចូលទីតាំងទទួល។',
            'dropoff_location.required' => 'សូមបញ្ចូលទីតាំងដឹកទៅ។',
            'pick_up_date.required'     => 'សូមជ្រើសរើសកាលបរិច្ឆេទទទួល។',
            'pick_up_date.after_or_equal' => 'កាលបរិច្ឆេទទទួលត្រូវតែជាថ្ងៃនេះ ឬក្រោយ។',
            'drop_off_date.required'    => 'សូមជ្រើសរើសកាលបរិច្ឆេទដឹកទៅ។',
            'drop_off_date.after_or_equal' => 'កាលបរិច្ឆេទដឹកទៅត្រូវតែក្រោយថ្ងៃទទួល។',
            'cargo_weight.required'     => 'សូមបញ្ចូលទម្ងន់ទំនិញ។',
            'cargo_weight.min'          => 'ទម្ងន់ទំនិញត្រូវតែធំជាង 0។',
        ]);

        // Create or find customer
        $customer = Customer::create([
            'full_name'    => $request->full_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'company_name' => $request->company_name,
        ]);

        // Create booking
        Booking::create([
            'customer_id'      => $customer->customer_id,
            'booking_type'     => $request->booking_type,
            'container_number' => $request->container_number,
            'pickup_location'  => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'pick_up_date'     => $request->pick_up_date,
            'drop_off_date'    => $request->drop_off_date,
            'cargo_weight'     => $request->cargo_weight,
            'booking_date'     => now()->toDateString(),
            'total_price'      => $request->total_price,
            'status'           => 'pending',
        ]);

        return redirect()->route('trucks_section')
            ->with('booking_success', 'ការកក់រថយន្តបានជោគជ័យ! យើងនឹងទាក់ទងអ្នកក្នុងពេលឆាប់ៗ។');
    }
}