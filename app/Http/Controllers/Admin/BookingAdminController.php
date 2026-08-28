<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\Customer;
use App\Models\ExtraCharge;
use App\Models\ShippingRate;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingAdminController extends Controller
{
    public const CHARGE_TYPE_LABELS = [
        'extra_charge' => 'ការគិតប្រាក់បន្ថែម (Extra Charge)',
        'empty_return' => 'ត្រឡប់ទទេ (Empty Return)',
        'overweight'   => 'ទម្ងន់លើស (Over Weight)',
        'standby'      => 'ឈប់រង់ចាំ (Standby)',
    ];

    public function store(Request $request)
    {
        if ($request->filled('container_number')) {
            $request->merge(['container_number' => strtoupper(trim($request->container_number))]);
        }

        $request->validate([
            'booked_by_user_id' => 'required|exists:tbl_user,user_id',
            'truck_id'          => 'required|exists:tbl_truck,truck_id',
            'booking_type'     => 'required|in:import,export',
            'container_number' => ['nullable','string','max:50','regex:/^[A-Z]{3}U[0-9]{7}$/'],
            'container_size'   => 'nullable|in:20F,40F,45F',
            'pickup_location'  => 'required|string|max:200',
            'dropoff_location' => 'required|string|max:200',
            'dropoff_location_link' => 'nullable|url|max:500',
            'pick_up_date'     => 'required|date',
            'drop_off_date'    => 'required|date|after_or_equal:pick_up_date',
            'cargo_weight'     => 'required|numeric|min:1',
            'total_price'      => 'nullable|numeric|min:0',
            'cargo_list_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'booked_by_user_id.required' => 'សូមជ្រើសរើសអ្នកស្នើការដឹក។',
            'booked_by_user_id.exists'  => 'អ្នកប្រើប្រាស់ដែលបានជ្រើសរើសមិនត្រឹមត្រូវ។',
            'truck_id.required'         => 'សូមជ្រើសរើសរថយន្ត។',
            'booking_type.required'     => 'សូមជ្រើសរើសប្រភេទការដឹកជញ្ជូន។',
            'pickup_location.required'  => 'សូមបញ្ចូលទីតាំងទទួល។',
            'dropoff_location.required' => 'សូមបញ្ចូលទីតាំងដឹកទៅ។',
            'pick_up_date.required'     => 'សូមជ្រើសរើសកាលបរិច្ឆេទទទួលទំនិញ។',
            'drop_off_date.required'    => 'សូមជ្រើសរើសកាលបរិច្ឆេទដឹកទៅ។',
            'drop_off_date.after_or_equal' => 'កាលបរិច្ឆេទដឹកទៅត្រូវតែក្រោយថ្ងៃទទួល។',
            'cargo_weight.required'     => 'សូមបញ្ចូលទម្ងន់ទំនិញ។',
            'cargo_weight.min'          => 'ទម្ងន់ទំនិញត្រូវតែធំជាង 0។',
            'container_number.regex'    => 'លេខកុងតឺន័រត្រូវមានទម្រង់ (អក្សរ ៤ តួ ចុងជា U + លេខ ៧ ខ្ទង់ — ឧ. TIIU1234567)។',
            'cargo_list_file.mimes'     => 'ឯកសារត្រូវតែជា PDF, JPG, ឬ PNG។',
            'cargo_list_file.max'       => 'ឯកសារធំពេក (អតិបរមា 5MB)។',
        ]);

        // Container number uniqueness check
        $containerNumber = trim($request->container_number ?? '');
        if ($containerNumber !== '') {
            $containerNumber = strtoupper($containerNumber);
            if (Booking::where('container_number', $containerNumber)->exists()) {
                return back()->withInput()
                    ->withErrors(['container_number' => 'លេខកុងតឺន័រ ' . $containerNumber . ' ត្រូវបានប្រើប្រាស់រួចហើយ។']);
            }
        } else {
            $containerNumber = null;
        }

        // Cargo file upload
        $filePath = null;
        if ($request->hasFile('cargo_list_file')) {
            $file     = $request->file('cargo_list_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cargo'), $filename);
            $filePath = 'uploads/cargo/' . $filename;
        }

        $booking = Booking::create([
            'booked_by_user_id'    => $request->booked_by_user_id,
            'truck_id'              => $request->truck_id,
            'booking_type'          => $request->booking_type,
            'container_number'      => $containerNumber,
            'container_size'        => $request->container_size,
            'pickup_location'       => $request->pickup_location,
            'dropoff_location'      => $request->dropoff_location,
            'dropoff_location_link' => $request->dropoff_location_link,
            'pick_up_date'          => $request->pick_up_date,
            'drop_off_date'         => $request->drop_off_date,
            'cargo_weight'          => $request->cargo_weight,
            'total_price'           => $request->total_price,
            'cargo_list_file'       => $filePath,
            'booking_date'          => now()->toDateString(),
            'status'                => 'pending',
            'payment_status'        => 'unpaid',
            'access_token'          => Str::random(40),
        ]);

        // Make this booking visible on /my-bookings for the current session
        session()->push('my_booking_ids', $booking->booking_id);
        session(['booking_token_' . $booking->booking_id => $booking->access_token]);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'ការកក់ ' . $booking->formatted_id . ' ត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'truck', 'extraCharges', 'bookedByUser']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('customer', fn($q) =>
                $q->where('full_name','like','%'.$request->search.'%')
                  ->orWhere('phone','like','%'.$request->search.'%')
            );
        }

        if ($request->filled('booking_code')) {
            $code = trim($request->booking_code);
            if (str_contains($code, '-')) {
                $parts = explode('-', $code);
                $id = (int) end($parts);
                if ($id > 0) $query->where('booking_id', $id);
            } elseif (is_numeric($code)) {
                $query->where('booking_id', (int) $code);
            }
        }

        $bookings   = $query->latest()->paginate(10)->withQueryString();
        $customers  = Customer::orderBy('full_name')->get();
        $staffUsers = User::whereIn('role', ['admin', 'operation', 'accountant'])
                          ->orderBy('user_name')->get();
        $trucks    = Truck::where('status', 'available')->orderBy('truck_name')->get();
        $provinces = ShippingRate::provinces();
        $ratesJson = ShippingRate::all(['type', 'origin', 'province_name_km', 'base_price']);
        return view('admin.bookings.index', compact('bookings', 'customers', 'staffUsers', 'trucks', 'provinces', 'ratesJson'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'ការកក់នេះបានបញ្ចប់ ឬបានលុបចោលរួចហើយ — មិនអាចផ្លាស់ប្ដូរស្ថានភាពបានទេ!');
        }

        $request->validate([
            'status'                  => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'total_price'             => 'nullable|numeric|min:0',
            'completion_charge_type'  => 'nullable|in:extra_charge,empty_return,overweight,standby',
            'completion_days'         => 'nullable|integer|min:1',
            'completion_amount'       => 'nullable|numeric|min:0.01',
            'completion_note'         => 'nullable|string|max:500',
        ]);

        $data = ['status' => $request->status];

        // Save total price when admin confirms (required for 50/50 payment split)
        if ($request->status === 'confirmed' && $request->filled('total_price')) {
            $data['total_price'] = $request->total_price;
        }

        $booking->update($data);

        // Sync the linked truck's status with the booking's lifecycle
        if ($booking->truck) {
            if ($request->status === 'confirmed') {
                $booking->truck->update(['status' => 'in_progress']);
            } elseif (in_array($request->status, ['completed', 'cancelled'])) {
                $booking->truck->update(['status' => 'available']);
            }
        }

        // Handle optional extra charge added at completion time (stage='second', NOT added to total_price)
        if ($request->status === 'completed' && $request->filled('completion_charge_type')) {
            $chargeType = $request->completion_charge_type;
            $chargeAmount = $chargeType === 'standby'
                ? $request->completion_days * 50
                : $request->completion_amount;

            if ($chargeAmount > 0) {
                $reason = self::CHARGE_TYPE_LABELS[$chargeType];
                if ($chargeType === 'standby') {
                    $reason .= ' — ' . $request->completion_days . ' ថ្ងៃ x $50';
                }
                if ($request->filled('completion_note')) {
                    $reason .= ' (' . $request->completion_note . ')';
                }

                ExtraCharge::create([
                    'booking_id' => $booking->booking_id,
                    'stage'      => 'second',
                    'amount'     => $chargeAmount,
                    'reason'     => $reason,
                    'date'       => now()->toDateString(),
                ]);
                // Do NOT add to total_price — second-stage charges are added in full to the final payment
            }
        }

        if ($request->status === 'completed') {
            BookingStatusHistory::create([
                'booking_id'     => $booking->booking_id,
                'total_price'    => $booking->fresh()->total_price,
                'completed_date' => now()->toDateString(),
            ]);
        }

        $msgs = [
            'pending'     => 'ការកក់ត្រូវបានកំណត់ជារង់ចាំ!',
            'confirmed'   => 'ការកក់បានអនុម័ត! អតិថិជននឹងបង់ 50% ដំបូង។',
            'in_progress' => 'ស្ថានភាពបានផ្លាស់ប្ដូរទៅ "កំពុងដឹក"!',
            'completed'   => 'ការដឹកបានបញ្ចប់! អតិថិជននឹងបង់ 50% ចុងក្រោយ។',
            'cancelled'   => 'ការកក់ត្រូវបានលុបចោល!',
        ];

        return back()->with('success', $msgs[$request->status] ?? 'ស្ថានភាពការកក់បានកែប្រែ!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'ការកក់ត្រូវបានលុប!');
    }

    public function storeExtraCharge(Request $request, Booking $booking)
    {
        $request->validate([
            'charge_type' => 'required|in:extra_charge,empty_return,overweight,standby',
            'days'        => 'required_if:charge_type,standby|nullable|integer|min:1',
            'amount'      => 'required_unless:charge_type,standby|nullable|numeric|min:0.01',
            'note'        => 'nullable|string|max:500',
        ]);

        $amount = $request->charge_type === 'standby'
            ? $request->days * 50
            : $request->amount;

        $reason = self::CHARGE_TYPE_LABELS[$request->charge_type];
        if ($request->charge_type === 'standby') {
            $reason .= ' — ' . $request->days . ' ថ្ងៃ x $50';
        }
        if ($request->filled('note')) {
            $reason .= ' (' . $request->note . ')';
        }

        // Standby is ALWAYS second-stage (paid 100% in final payment, never split 50/50)
        // Other charges: second-stage if already completed, first-stage otherwise
        $stage = ($booking->status === 'completed' || $request->charge_type === 'standby')
            ? 'second'
            : 'first';

        ExtraCharge::create([
            'booking_id' => $booking->booking_id,
            'stage'      => $stage,
            'amount'     => $amount,
            'reason'     => $reason,
            'date'       => now()->toDateString(),
        ]);

        if ($stage === 'first') {
            // First-stage charges are folded into total_price so the 50/50 split includes them
            $newTotal = ($booking->total_price ?? 0) + $amount;
            $booking->update(['total_price' => $newTotal]);
            return back()->with('success', 'ការគិតប្រាក់បន្ថែមត្រូវបានបន្ថែម! តម្លៃសរុបថ្មី = $' . number_format($newTotal, 2));
        }

        // Second-stage: customer pays this charge in FULL on top of remaining 50%
        $secondTotal = $booking->extraCharges()->where('stage', 'second')->sum('amount');
        $finalPayment = round(($booking->total_price ?? 0) * 0.5, 2) + $secondTotal;
        $label = $request->charge_type === 'standby' ? 'Standby (ទូទាត់ 100% ក្នុងការទូទាត់ចុងក្រោយ)' : 'ការទូទាត់ចុងក្រោយ';
        return back()->with('success', "ការគិតប្រាក់បន្ថែម ({$label}) ត្រូវបានបន្ថែម! សរុបការទូទាត់ចុងក្រោយ = \$" . number_format($finalPayment, 2));
    }
}
