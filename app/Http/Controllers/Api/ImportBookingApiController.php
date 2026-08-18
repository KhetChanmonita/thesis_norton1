<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImportBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImportBookingApiController extends Controller
{
    public function index()
    {
        return response()->json(ImportBooking::latest()->get());
    }

    public function show(int $id)
    {
        $booking = ImportBooking::findOrFail($id);
        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name'           => 'required|string|max:255',
            'bill_booking'           => 'required|string|max:255',
            'container_size'         => 'required|string|max:50',
            'container_price'        => 'required|numeric|min:0',
            'pickup_date'            => 'required|date',
            'delivery_date'          => 'required|date',
            'pickup_location'        => 'required|string|max:255',
            'dropoff_location'       => 'required|string|max:255',
            'dropoff_location_link'  => 'nullable|string|max:500',
            'document_holder_phone'  => 'required|string|max:20',
            'delivery_contact_phone' => 'required|string|max:20',
            'truck_id'               => 'nullable|integer|exists:tbl_truck,truck_id',
            'cargo_weight'           => 'nullable|numeric|min:0',
            'cargo_list_files'       => 'nullable|array',
            'cargo_list_files.*'     => 'file|mimes:jpg,jpeg,png,pdf|max:5120',
            'customer_full_name'     => 'nullable|string|max:255',
            'customer_email'         => 'nullable|email|max:255',
            'customer_address'       => 'nullable|string|max:500',
            'customer_phone'         => 'nullable|string|max:20',
        ]);

        $fileUrls = [];
        if ($request->hasFile('cargo_list_files')) {
            foreach ($request->file('cargo_list_files') as $file) {
                $path = $file->store('import-bookings/cargo', 'public');
                $fileUrls[] = $path;
            }
        }

        $booking = ImportBooking::create([
            ...$data,
            'cargo_list_file_urls' => $fileUrls,
            'booking_code'         => 'IMP-' . strtoupper(Str::random(8)),
            'status'               => 'pending',
            'payment_status'       => 'unpaid',
        ]);

        return response()->json($booking, 201);
    }

    public function update(Request $request, int $id)
    {
        $booking = ImportBooking::findOrFail($id);

        $data = $request->validate([
            'company_name'           => 'sometimes|string|max:255',
            'bill_booking'           => 'sometimes|string|max:255',
            'container_size'         => 'sometimes|string|max:50',
            'container_price'        => 'sometimes|numeric|min:0',
            'pickup_date'            => 'sometimes|date',
            'delivery_date'          => 'sometimes|date',
            'pickup_location'        => 'sometimes|string|max:255',
            'dropoff_location'       => 'sometimes|string|max:255',
            'dropoff_location_link'  => 'nullable|string|max:500',
            'document_holder_phone'  => 'sometimes|string|max:20',
            'delivery_contact_phone' => 'sometimes|string|max:20',
            'truck_id'               => 'nullable|integer|exists:tbl_truck,truck_id',
            'cargo_weight'           => 'nullable|numeric|min:0',
            'status'                 => 'sometimes|string|in:pending,confirmed,in_progress,completed,cancelled',
            'payment_status'         => 'sometimes|string|in:unpaid,deposit_paid,fully_paid',
            'customer_full_name'     => 'nullable|string|max:255',
            'customer_email'         => 'nullable|email|max:255',
            'customer_address'       => 'nullable|string|max:500',
            'customer_phone'         => 'nullable|string|max:20',
        ]);

        $booking->update($data);

        return response()->json($booking->fresh());
    }

    public function destroy(int $id)
    {
        ImportBooking::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}