<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CostSheet;
use Illuminate\Http\Request;

class CostSheetAdminController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $monthNum] = explode('-', $month);

        $query = Booking::with(['customer', 'truck', 'costSheet'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum);

        if ($request->filled('search')) {
            $query->whereHas('customer', fn($c) =>
                $c->where('full_name', 'like', '%' . $request->search . '%')
            );
        }

        $bookings = $query->orderBy('booking_date')->paginate(20)->withQueryString();

        return view('admin.reports.cost-sheet', compact('bookings', 'month'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'route'         => 'nullable|string|max:100',
            'size'          => 'nullable|string|max:20',
            'price'         => 'nullable|numeric|min:0',
            'lolo'          => 'nullable|numeric|min:0',
            'over_weight'   => 'nullable|numeric|min:0',
            'express_way'   => 'nullable|numeric|min:0',
            'extra'         => 'nullable|numeric|min:0',
            'empty_return'  => 'nullable|numeric|min:0',
            'standby_truck' => 'nullable|numeric|min:0',
            'repair'        => 'nullable|numeric|min:0',
        ]);

        $costSheet = CostSheet::updateOrCreate(
            ['booking_id' => $booking->booking_id],
            $request->only([
                'route', 'size', 'price', 'lolo', 'over_weight', 'express_way',
                'extra', 'empty_return', 'standby_truck', 'repair',
            ])
        );

        return response()->json(['success' => true, 'total' => $costSheet->total]);
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['customer', 'truck', 'costSheet']);
        return view('admin.reports.invoice', compact('booking'));
    }
}
