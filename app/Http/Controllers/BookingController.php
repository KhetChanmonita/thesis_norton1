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
        if ($request->filled('container_number')) {
            $request->merge(['container_number' => strtoupper(trim($request->container_number))]);
        }

        $request->validate([
            'full_name'        => 'required|string|max:100',
            'phone'            => 'required|string|max:20',
            'email'            => 'nullable|email',
            'booking_type'     => 'required|in:import,export',
            'container_number' => ['nullable', 'string', 'max:50', 'regex:/^[A-Z]{3}U[0-9]{7}$/'],
            'container_size'   => 'nullable|in:20F,40F,45F',
            'pickup_location'  => 'required|string|max:200',
            'dropoff_location' => 'required|string|max:200',
            'dropoff_location_link' => 'nullable|url|max:500',
            'pick_up_date'     => 'required|date|after_or_equal:today',
            'drop_off_date'    => 'required|date|after_or_equal:pick_up_date',
            'cargo_weight'     => 'required|numeric|min:1',
            'cargo_list_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'full_name.required'           => 'សូមបញ្ចូលឈ្មោះពេញ។',
            'phone.required'               => 'សូមបញ្ចូលលេខទូរស័ព្ទ។',
            'booking_type.required'        => 'សូមជ្រើសរើសប្រភេទការដឹកជញ្ជូន។',
            'pickup_location.required'     => 'សូមបញ្ចូលទីតាំងទទួល។',
            'dropoff_location.required'    => 'សូមបញ្ចូលទីតាំងដឹកទៅ។',
            'pick_up_date.required'        => 'សូមជ្រើសរើសកាលបរិច្ឆេទទទួល។',
            'pick_up_date.after_or_equal'  => 'កាលបរិច្ឆេទទទួលត្រូវតែជាថ្ងៃនេះ ឬក្រោយ។',
            'drop_off_date.required'       => 'សូមជ្រើសរើសកាលបរិច្ឆេទដឹកទៅ។',
            'drop_off_date.after_or_equal' => 'កាលបរិច្ឆេទដឹកទៅត្រូវតែក្រោយថ្ងៃទទួល។',
            'cargo_weight.required'        => 'សូមបញ្ចូលទម្ងន់ទំនិញ។',
            'cargo_weight.min'             => 'ទម្ងន់ទំនិញត្រូវតែធំជាង 0។',
            'container_number.regex'       => 'លេខកុងតឺន័រត្រូវមានទម្រង់ត្រឹមត្រូវ (អក្សរ ៤ តួ ដែលតួចុងជា U បន្តដោយលេខ ៧ ខ្ទង់ — ឧ. TIIU1234567)។',
            'cargo_list_file.mimes'        => 'ឯកសារត្រូវតែជា PDF, JPG, ឬ PNG។',
            'cargo_list_file.max'          => 'ឯកសារធំពេក (អតិបរមា 5MB)។',
            'dropoff_location_link.url'    => 'តំណទីតាំងមិនត្រឹមត្រូវ — សូមបញ្ចូល URL ត្រឹមត្រូវ (ឧ. ពីGoogle Maps)។',
        ]);

        // Guard: check truck availability + active driver
        $selectedTruck = null;
        if ($request->filled('truck_code')) {
            $selectedTruck = Truck::with('drivers')
                ->where('plate_number', $request->truck_code)
                ->first();

            if ($selectedTruck) {
                if ($selectedTruck->status !== 'available') {
                    $reason = $selectedTruck->status === 'delivering'
                        ? 'រថយន្ត ' . $selectedTruck->plate_number . ' កំពុងដឹកទំនិញ'
                        : 'រថយន្ត ' . $selectedTruck->plate_number . ' កំពុងជួសជុល';
                    return back()->withInput()
                        ->withErrors(['truck_code' => $reason . ' — សូមជ្រើសរើសរថយន្តផ្សេង']);
                }

                $hasActiveDriver = $selectedTruck->drivers
                    ->where('status', 'active')->count() > 0;

                if (!$hasActiveDriver) {
                    return back()->withInput()
                        ->withErrors(['truck_code' =>
                            'រថយន្ត ' . $selectedTruck->plate_number .
                            ' មិនមានអ្នកបើកបរអាចប្រើប្រាស់ — សូមជ្រើសរើសរថយន្តផ្សេង']);
                }
            }
        }

        // Manually check container_number uniqueness — only when a non-empty value is given
        $containerNumber = trim($request->container_number ?? '');
        if ($containerNumber !== '') {
            $containerNumber = strtoupper($containerNumber);
            if (Booking::where('container_number', $containerNumber)->exists()) {
                return back()
                    ->withInput()
                    ->withErrors(['container_number' => 'លេខកុងតឺន័រ ' . $containerNumber . ' ត្រូវបានប្រើប្រាស់រួចហើយ។']);
            }
        } else {
            $containerNumber = null;
        }

        // Handle cargo document upload
        $filePath = null;
        if ($request->hasFile('cargo_list_file')) {
            $file     = $request->file('cargo_list_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cargo'), $filename);
            $filePath = 'uploads/cargo/' . $filename;
        }

        $customer = Customer::create([
            'full_name'    => $request->full_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address ?? null,
            'company_name' => $request->company_name ?? null,
        ]);

        $accessToken = \Illuminate\Support\Str::random(40);

        $booking = Booking::create([
            'customer_id'      => $customer->customer_id,
            'truck_id'         => $selectedTruck->truck_id ?? null,
            'booking_type'     => $request->booking_type,
            'container_number' => $containerNumber,
            'container_size'   => $request->container_size ?? null,
            'pickup_location'  => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'dropoff_location_link' => $request->dropoff_location_link,
            'pick_up_date'     => $request->pick_up_date,
            'drop_off_date'    => $request->drop_off_date,
            'cargo_weight'     => $request->cargo_weight,
            'booking_date'     => now()->toDateString(),
            'total_price'      => $request->total_price ?? null,
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
            'cargo_list_file'  => $filePath,
            'access_token'     => $accessToken,
        ]);

        if ($selectedTruck) {
            $selectedTruck->update(['status' => 'in_progress']);
        }

        session()->push('my_booking_ids', $booking->booking_id);

        return redirect()->route('booking.track', [
            'id'    => $booking->booking_id,
            'token' => $accessToken,
        ]);
    }

    // ── Customer: my bookings & payments ──────────────────────────────────
    public function myBookings(Request $request)
    {
        $bookings        = collect();
        $searched        = false;
        $searchPhone     = '';
        $searchContainer = '';

        if ($request->filled('container_number')) {
            // Lookup by container number
            $searchContainer = trim($request->container_number);
            $searched        = true;

            $bookings = Booking::with(['customer', 'payments'])
                ->where('container_number', $searchContainer)
                ->latest()
                ->get();

            // Persist found IDs + tokens in session so tracking links work
            $sessionIds = session('my_booking_ids', []);
            foreach ($bookings as $b) {
                if (!in_array($b->booking_id, $sessionIds)) {
                    session()->push('my_booking_ids', $b->booking_id);
                }
                if ($b->access_token) {
                    session(['booking_token_' . $b->booking_id => $b->access_token]);
                }
            }
        } elseif ($request->filled('phone')) {
            // Lookup by phone — find all bookings for this customer
            $searchPhone = trim($request->phone);
            $searched    = true;

            $bookings = Booking::with(['customer', 'payments'])
                ->whereHas('customer', fn($q) => $q->where('phone', $searchPhone))
                ->latest()
                ->get();

            // Persist found IDs + tokens in session so tracking links work
            $sessionIds = session('my_booking_ids', []);
            foreach ($bookings as $b) {
                if (!in_array($b->booking_id, $sessionIds)) {
                    session()->push('my_booking_ids', $b->booking_id);
                }
                if ($b->access_token) {
                    session(['booking_token_' . $b->booking_id => $b->access_token]);
                }
            }
        } else {
            // Fall back to session-stored IDs
            $ids = array_unique(session('my_booking_ids', []));
            if (!empty($ids)) {
                $bookings = Booking::with(['customer', 'payments'])
                    ->whereIn('booking_id', $ids)
                    ->latest()
                    ->get();
            }
        }

        $totalPaid    = $bookings->flatMap->payments->sum('amount');
        $pendingCount = $bookings->whereIn('payment_status', ['unpaid', 'deposit_paid'])
                                 ->where('status', '!=', 'cancelled')->count();

        return view('bookings.my-bookings',
            compact('bookings', 'totalPaid', 'pendingCount', 'searched', 'searchPhone', 'searchContainer')
        );
    }

    // ── Booking tracking ───────────────────────────────────────────────────
    public function trackBooking(Request $request, $id)
    {
        $booking   = Booking::with(['customer', 'extraCharges', 'payments'])->findOrFail($id);
        $token     = $request->query('token') ?? session('booking_token_' . $id);
        $inSession = in_array((int)$id, session('my_booking_ids', []));

        if (!$inSession && $token !== $booking->access_token) {
            abort(403, 'Access denied.');
        }

        if ($token === $booking->access_token) {
            session(['booking_token_' . $id => $token]);
            if (!$inSession) {
                session()->push('my_booking_ids', (int)$id);
            }
        }

        return view('bookings.track', compact('booking'));
    }

    // ── Respond to an extra charge (accept / reject) ───────────────────────
    public function respondExtraCharge(Request $request, $extraChargeId)
    {
        $charge  = \App\Models\ExtraCharge::with('booking')->findOrFail($extraChargeId);
        $booking = $charge->booking;

        $token     = $request->input('token') ?? session('booking_token_' . $booking->booking_id);
        $inSession = in_array((int)$booking->booking_id, session('my_booking_ids', []));

        if (!$inSession && $token !== $booking->access_token) {
            abort(403);
        }

        $request->validate(['response' => 'required|in:Accepted,Rejected']);

        $charge->update(['client_response' => $request->response]);

        $msg = $request->response === 'Accepted'
            ? 'អ្នកបានយល់ព្រមលើការគិតប្រាក់បន្ថែម!'
            : 'អ្នកបានបដិសេធការគិតប្រាក់បន្ថែម!';

        return redirect()->route('booking.track', [
            'id' => $booking->booking_id, 'token' => $booking->access_token,
        ])->with('extra_charge_response', $msg);
    }

    // ── Show payment form ──────────────────────────────────────────────────
    public function showPayment(Request $request, $id)
    {
        $booking   = Booking::with('customer')->findOrFail($id);
        $token     = $request->query('token') ?? session('booking_token_' . $id);
        $inSession = in_array((int)$id, session('my_booking_ids', []));

        if (!$inSession && $token !== $booking->access_token) {
            abort(403);
        }

        if ($booking->payment_status === 'unpaid' && $booking->status === 'confirmed') {
            $stage  = 'deposit';
            $amount = round($booking->total_price * 0.5, 2);
            $label  = 'ការបង់ប្រាក់ 50% ដំបូង (Deposit)';
        } elseif ($booking->payment_status === 'deposit_paid' && $booking->status === 'completed') {
            $hasPendingFinal = $booking->payments()
                ->where('payment_stage', 'second')
                ->where('verification_status', 'pending')
                ->exists();
            if ($hasPendingFinal) {
                return redirect()->route('booking.track', ['id' => $id, 'token' => $booking->access_token]);
            }
            $stage  = 'final';
            $amount = round($booking->total_price * 0.5, 2);
            $label  = 'ការបង់ប្រាក់ 50% ចុងក្រោយ (Final)';
        } else {
            return redirect()->route('booking.track', [
                'id' => $id, 'token' => $booking->access_token,
            ]);
        }

        return view('bookings.payment', compact('booking', 'stage', 'amount', 'label', 'token'));
    }

    // ── Process payment ────────────────────────────────────────────────────
    public function processPayment(Request $request, $id)
    {
        $booking   = Booking::with(['customer', 'truck'])->findOrFail($id);
        $token     = $request->input('token') ?? session('booking_token_' . $id);
        $inSession = in_array((int)$id, session('my_booking_ids', []));

        if (!$inSession && $token !== $booking->access_token) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|string|max:50',
            'payment_proof'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'payment_proof.required' => 'សូមបញ្ចូលរូបភាព ឬឯកសារភស្តុតាងការទូទាត់។',
            'payment_proof.mimes'    => 'ឯកសារត្រូវតែជា JPG, PNG, ឬ PDF។',
            'payment_proof.max'      => 'ឯកសារធំពេក (អតិបរមា 5MB)។',
        ]);

        $proofFile = $request->file('payment_proof');
        $proofFilename = time() . '_' . $proofFile->getClientOriginalName();
        $proofFile->move(public_path('uploads/payment-proofs'), $proofFilename);
        $proofPath = 'uploads/payment-proofs/' . $proofFilename;

        if ($booking->payment_status === 'unpaid' && $booking->status === 'confirmed') {
            $amount = round($booking->total_price * 0.5, 2);
            \App\Models\Payment::create([
                'booking_id'            => $booking->booking_id,
                'amount'                => $amount,
                'payment_method'        => $request->payment_method,
                'payment_stage'         => 'first',
                'payment_date'          => today()->toDateString(),
                'transaction_reference' => 'DEP-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'proof_file'            => $proofPath,
            ]);
            $booking->update(['payment_status' => 'deposit_paid']);
            $msg = 'ការបង់ប្រាក់ 50% ដំបូងបានទទួល! សូមរង់ចាំការផ្ទៀងផ្ទាត់ពីអ្នកគ្រប់គ្រង។';

        } elseif ($booking->payment_status === 'deposit_paid' && $booking->status === 'completed') {
            $amount = round($booking->total_price * 0.5, 2);
            \App\Models\Payment::create([
                'booking_id'            => $booking->booking_id,
                'amount'                => $amount,
                'payment_method'        => $request->payment_method,
                'payment_stage'         => 'second',
                'payment_date'          => today()->toDateString(),
                'transaction_reference' => 'FIN-' . strtoupper(\Illuminate\Support\Str::random(8)),
                'proof_file'            => $proofPath,
                'verification_status'   => 'pending',
            ]);
            $msg = 'ការបង់ប្រាក់ 50% ចុងក្រោយបានទទួល! សូមរង់ចាំការផ្ទៀងផ្ទាត់ពីអ្នកគ្រប់គ្រង។';

        } else {
            return redirect()->route('booking.track', [
                'id' => $id, 'token' => $booking->access_token,
            ]);
        }

        return redirect()->route('booking.track', [
            'id' => $id, 'token' => $booking->access_token,
        ])->with('payment_success', $msg);
    }
}