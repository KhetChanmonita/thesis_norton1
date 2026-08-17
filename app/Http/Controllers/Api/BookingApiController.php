<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Truck;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    // GET /api/bookings?phone=012345678
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'truck'])
            ->orderBy('booking_id', 'desc');

        if ($request->filled('phone')) {
            $query->whereHas('customer', fn($q) => $q->where('phone', $request->phone));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => $bookings->map(fn($b) => $this->summary($b)),
            'meta'    => [
                'total'        => $bookings->total(),
                'current_page' => $bookings->currentPage(),
                'last_page'    => $bookings->lastPage(),
            ],
        ]);
    }

    // GET /api/bookings/{id}
    public function show($id)
    {
        $booking = Booking::with(['customer', 'truck', 'payments', 'statusHistory'])
            ->find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->detail($booking),
        ]);
    }

    // GET /api/bookings/track/{token}  — track by access_token (no login needed)
    public function track($token)
    {
        $booking = Booking::with(['customer', 'truck', 'payments', 'statusHistory'])
            ->where('access_token', $token)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->detail($booking),
        ]);
    }

    // POST /api/bookings
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'       => 'required|exists:tbl_customer,customer_id',
            'truck_id'          => 'required|exists:tbl_truck,truck_id',
            'booking_type'      => 'required|in:import,export',
            'container_number'  => 'nullable|string|max:50',
            'container_size'    => 'nullable|in:20GP,40DC,40HC,45HC',
            'pickup_location'   => 'required|string|max:255',
            'dropoff_location'  => 'required|string|max:255',
            'pick_up_date'      => 'required|date',
            'drop_off_date'     => 'nullable|date|after_or_equal:pick_up_date',
            'cargo_weight'      => 'nullable|numeric|min:0',
            'total_price'       => 'nullable|numeric|min:0',
        ]);

        $validated['status']         = 'pending';
        $validated['payment_status'] = 'unpaid';
        $validated['booking_date']   = now();
        $validated['access_token']   = bin2hex(random_bytes(16));

        $booking = Booking::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data'    => $this->summary($booking->load(['customer', 'truck'])),
        ], 201);
    }

    private function summary(Booking $b): array
    {
        return [
            'booking_id'     => $b->booking_id,
            'formatted_id'   => $b->formatted_id,
            'booking_type'   => $b->booking_type,
            'status'         => $b->status,
            'payment_status' => $b->payment_status,
            'pickup'         => $b->pickup_location,
            'dropoff'        => $b->dropoff_location,
            'pick_up_date'   => $b->pick_up_date?->format('Y-m-d'),
            'drop_off_date'  => $b->drop_off_date?->format('Y-m-d'),
            'total_price'    => $b->total_price,
            'customer'       => [
                'name'  => $b->customer?->full_name,
                'phone' => $b->customer?->phone,
            ],
            'truck' => [
                'plate'  => $b->truck?->plate_number,
                'name'   => $b->truck?->truck_name,
            ],
        ];
    }

    private function detail(Booking $b): array
    {
        return array_merge($this->summary($b), [
            'container_number' => $b->container_number,
            'container_size'   => $b->container_size,
            'cargo_weight'     => $b->cargo_weight,
            'booking_date'     => $b->booking_date?->format('Y-m-d'),
            'access_token'     => $b->access_token,
            'payments' => $b->payments->map(fn($p) => [
                'amount'         => $p->amount,
                'payment_stage'  => $p->payment_stage,
                'payment_date'   => $p->payment_date,
                'payment_method' => $p->payment_method,
            ])->values(),
            'status_history' => $b->statusHistory->map(fn($h) => [
                'status'     => $h->status,
                'note'       => $h->note,
                'changed_at' => $h->created_at?->format('Y-m-d H:i'),
            ])->values(),
        ]);
    }
}