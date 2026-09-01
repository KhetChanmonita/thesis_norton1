<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Truck;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseAdminController extends Controller
{
    public function index(Request $request)
    {
        $month = $this->resolveMonth($request);
        [$year, $monthNum] = explode('-', $month);

        $query = Expense::with(['truck', 'driver'])
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum);

        if ($request->filled('expense_type')) {
            $query->where('expense_type', $request->expense_type);
        }

        $expenses = $query->latest('expense_date')->paginate(10)->withQueryString();

        $totals = Expense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum)
            ->selectRaw('expense_type, SUM(amount) as total')
            ->groupBy('expense_type')
            ->pluck('total', 'expense_type');

        $driverAllowanceSum = Expense::whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum)
            ->sum('driver_allowance');
        $grandTotal = $totals->sum() + (float) $driverAllowanceSum;

        $monthlyRevenue = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum)
            ->where('verification_status', 'verified')
            ->sum('amount');
        $monthlyProfit  = $monthlyRevenue - $grandTotal;

        // 6-month trend (selected month + previous 5) for the analysis chart
        $current   = \Carbon\Carbon::createFromFormat('Y-m', $month);
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $m    = $current->copy()->subMonths($i);
            $sums = Expense::whereYear('expense_date', $m->year)
                ->whereMonth('expense_date', $m->month)
                ->selectRaw('expense_type, SUM(amount) as total')
                ->groupBy('expense_type')
                ->pluck('total', 'expense_type');

            $revenue = Payment::whereYear('payment_date', $m->year)
                ->whereMonth('payment_date', $m->month)
                ->where('verification_status', 'verified')
                ->sum('amount');

            $trendAllowance = (float) Expense::whereYear('expense_date', $m->year)
                ->whereMonth('expense_date', $m->month)
                ->sum('driver_allowance');

            $trendData[] = [
                'label'     => $m->format('m/Y'),
                'salary'    => (float) ($sums['salary'] ?? 0),
                'fuel'      => (float) ($sums['fuel'] ?? 0),
                'repair'    => (float) ($sums['repair'] ?? 0),
                'other'     => (float) ($sums['other'] ?? 0),
                'allowance' => $trendAllowance,
                'revenue'   => (float) $revenue,
                'profit'    => (float) $revenue - $sums->sum() - $trendAllowance,
            ];
        }

        $drivers  = Driver::orderBy('full_name')->get();
        $trucks   = Truck::orderBy('truck_name')->get();
        $bookings = Booking::with(['customer', 'truck'])->orderByDesc('booking_date')->get();

        return view('admin.reports.index', compact(
            'expenses', 'month', 'totals', 'grandTotal', 'driverAllowanceSum', 'drivers', 'trucks', 'trendData',
            'monthlyRevenue', 'monthlyProfit', 'bookings'
        ));
    }

    public function revenue(Request $request)
    {
        $month = $this->resolveMonth($request);
        [$year, $monthNum] = explode('-', $month);

        $bookings = Booking::with(['customer', 'bookedByUser', 'truck'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum)
            ->latest('booking_date')
            ->paginate(15)
            ->withQueryString();

        // Only sum bookings that have received at least a deposit (exclude unpaid & cancelled)
        $totalValue = Booking::whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum)
            ->where('payment_status', '!=', 'unpaid')
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        $totalCount = Booking::whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum)
            ->count();

        return view('admin.reports.revenue', compact('bookings', 'month', 'totalValue', 'totalCount'));
    }

    public function revenuePrint(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        [$year, $monthNum] = explode('-', $month);

        $bookings = Booking::with(['customer', 'bookedByUser', 'truck'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum)
            ->orderBy('booking_date')
            ->get();

        $totalValue = $bookings->where('payment_status', '!=', 'unpaid')->where('status', '!=', 'cancelled')->sum('total_price');
        $totalCount = $bookings->count();

        return view('admin.reports.revenue-print', compact('bookings', 'month', 'totalValue', 'totalCount'));
    }

    public function profit(Request $request)
    {
        $data = $this->buildProfitData($this->resolveMonth($request));
        return view('admin.reports.profit', $data);
    }

    public function profitPrint(Request $request)
    {
        $data = $this->buildProfitData($this->resolveMonth($request));
        return view('admin.reports.profit-print', $data);
    }

    /**
     * Resolve which month to display.
     * If the user didn't pick a month and the current month has no data,
     * fall back to the most recent month that has bookings, payments, or expenses.
     */
    private function resolveMonth(Request $request): string
    {
        $month = $request->input('month', now()->format('Y-m'));

        if ($request->filled('month')) {
            return $month;
        }

        [$cy, $cm] = explode('-', $month);
        $hasData = Booking::whereYear('booking_date', $cy)->whereMonth('booking_date', $cm)->exists()
                || Payment::whereYear('payment_date',  $cy)->whereMonth('payment_date',  $cm)->exists()
                || Expense::whereYear('expense_date',  $cy)->whereMonth('expense_date',  $cm)->exists();

        if ($hasData) {
            return $month;
        }

        $lastBooking = Booking::latest('booking_date')->value('booking_date');
        $lastPayment = Payment::latest('payment_date')->value('payment_date');
        $lastExpense = Expense::latest('expense_date')->value('expense_date');
        $candidates  = array_filter([$lastBooking, $lastPayment, $lastExpense]);

        return $candidates
            ? \Carbon\Carbon::parse(max($candidates))->format('Y-m')
            : $month;
    }

    /**
     * Shared revenue + expense breakdown for the profit report (screen + print).
     */
    private function buildProfitData(string $month): array
    {
        [$year, $monthNum] = explode('-', $month);

        $bookings = Booking::with(['customer', 'bookedByUser', 'truck'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum)
            ->orderBy('booking_date')
            ->get();

        $payments = Payment::with(['booking.customer', 'booking.bookedByUser'])
            ->whereYear('payment_date', $year)
            ->whereMonth('payment_date', $monthNum)
            ->where('verification_status', 'verified')
            ->orderBy('payment_date')
            ->get();

        $expenses = Expense::with(['truck', 'driver'])
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum)
            ->orderBy('expense_date')
            ->get();

        $expenseTotals = $expenses->groupBy('expense_type')
            ->map(fn($group) => $group->sum('amount') + $group->sum('driver_allowance'));

        $totalRevenue = $payments->sum('amount');
        $totalExpense = $expenses->sum('amount') + $expenses->sum('driver_allowance');
        $profit       = $totalRevenue - $totalExpense;

        return [
            'month'         => $month,
            'bookings'      => $bookings,
            'payments'      => $payments,
            'expenses'      => $expenses,
            'expenseTotals' => $expenseTotals,
            'totalRevenue'  => $totalRevenue,
            'totalExpense'  => $totalExpense,
            'profit'        => $profit,
        ];
    }

    public function fuelPrint(Request $request)
    {
        $month   = $request->input('month', now()->format('Y-m'));
        $truckId = $request->input('truck_id');
        [$year, $monthNum] = explode('-', $month);

        $query = Expense::with(['truck', 'driver', 'booking.customer', 'booking.bookedByUser', 'booking.truck'])
            ->where('expense_type', 'fuel')
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum);

        if ($truckId) {
            $query->where('truck_id', $truckId);
        }

        $fuels = $query->latest('expense_date')->get();

        $grandFuel      = $fuels->sum('amount');
        $grandAllowance = $fuels->sum('driver_allowance');
        $grandTotal     = $grandFuel + $grandAllowance;
        $selectedTruck  = $truckId ? Truck::find($truckId) : null;

        return view('admin.reports.fuel-print', compact(
            'fuels', 'month',
            'grandFuel', 'grandAllowance', 'grandTotal', 'selectedTruck'
        ));
    }

    public function fuelReport(Request $request)
    {
        $month   = $this->resolveMonth($request);
        $truckId = $request->input('truck_id');
        [$year, $monthNum] = explode('-', $month);

        $query = Expense::with(['truck', 'driver', 'booking.customer', 'booking.bookedByUser', 'booking.truck'])
            ->where('expense_type', 'fuel')
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum);

        if ($truckId) {
            $query->where('truck_id', $truckId);
        }

        $fuels = $query->latest('expense_date')->get();

        // Per-truck summary
        $truckSummary = $fuels->groupBy('truck_id')->map(fn($g) => [
            'truck'            => $g->first()->truck,
            'count'            => $g->count(),
            'total_fuel'       => $g->sum('amount'),
            'total_allowance'  => $g->sum('driver_allowance'),
            'total'            => $g->sum('amount') + $g->sum('driver_allowance'),
        ])->sortByDesc('total');

        // Booking IDs that already have a fuel expense recorded
        $bookedFuelIds = Expense::where('expense_type', 'fuel')
            ->whereNotNull('booking_id')
            ->pluck('booking_id')
            ->unique()
            ->values();

        // Only bookings without any fuel expense yet (for the Add modal)
        $availableBookings = Booking::with(['customer', 'bookedByUser', 'truck'])
            ->whereNotIn('booking_id', $bookedFuelIds)
            ->orderByDesc('booking_date')
            ->get();

        $trucks           = Truck::orderBy('truck_name')->get();
        $grandFuel        = $fuels->sum('amount');
        $grandAllowance   = $fuels->sum('driver_allowance');
        $grandTotal       = $grandFuel + $grandAllowance;
        $selectedTruck    = $truckId ? Truck::find($truckId) : null;

        // truck_id → driver_id (first assigned driver per truck)
        $truckDriverMap = Driver::whereNotNull('assigned_truck')
            ->orderBy('driver_id')
            ->pluck('driver_id', 'assigned_truck');

        // truck_id → { amount, allowance } from the most recent fuel expense per truck
        $truckLastFuelMap = Expense::where('expense_type', 'fuel')
            ->whereNotNull('truck_id')
            ->latest('expense_date')
            ->get()
            ->unique('truck_id')
            ->keyBy('truck_id')
            ->map(fn($e) => [
                'amount'    => (float) $e->amount,
                'allowance' => (float) ($e->driver_allowance ?? 0),
            ]);

        return view('admin.reports.fuel', compact(
            'fuels', 'month', 'trucks', 'truckId', 'truckSummary',
            'grandFuel', 'grandAllowance', 'grandTotal', 'selectedTruck',
            'availableBookings', 'truckDriverMap', 'truckLastFuelMap'
        ));
    }

    public function truckRepair(Request $request)
    {
        $month    = $this->resolveMonth($request);
        $truckId  = $request->input('truck_id');
        [$year, $monthNum] = explode('-', $month);

        $query = Expense::with(['truck'])
            ->where('expense_type', 'repair')
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum);

        if ($truckId) {
            $query->where('truck_id', $truckId);
        }

        $repairs = $query->latest('expense_date')->get();

        // Per-truck summary for the selected month
        $truckSummary = Expense::with('truck')
            ->where('expense_type', 'repair')
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum)
            ->get()
            ->groupBy('truck_id')
            ->map(fn($group) => [
                'truck'       => $group->first()->truck,
                'count'       => $group->count(),
                'total'       => $group->sum('amount'),
            ]);

        $trucks      = Truck::orderBy('truck_name')->get();
        $grandTotal  = $repairs->sum('amount');
        $repairCount = $repairs->count();
        $selectedTruck = $truckId ? Truck::find($truckId) : null;

        return view('admin.reports.truck-repair',
            compact('repairs', 'month', 'trucks', 'truckId', 'grandTotal', 'repairCount', 'truckSummary', 'selectedTruck'));
    }

    public function truckRepairPrint(Request $request)
    {
        $month    = $request->input('month', now()->format('Y-m'));
        $truckId  = $request->input('truck_id');
        [$year, $monthNum] = explode('-', $month);

        $query = Expense::with(['truck'])
            ->where('expense_type', 'repair')
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $monthNum);

        if ($truckId) {
            $query->where('truck_id', $truckId);
        }

        $repairs     = $query->latest('expense_date')->get();
        $grandTotal  = $repairs->sum('amount');
        $repairCount = $repairs->count();
        $selectedTruck = $truckId ? Truck::find($truckId) : null;
        $trucks      = Truck::orderBy('truck_name')->get();

        return view('admin.reports.truck-repair-print',
            compact('repairs', 'month', 'trucks', 'truckId', 'grandTotal', 'repairCount', 'selectedTruck'));
    }

    public function customerReport(Request $request)
    {
        $month      = $this->resolveMonth($request);
        $filterKey  = $request->input('filter_key'); // format: "c_5" or "u_3"
        [$year, $monthNum] = explode('-', $month);

        $query = Booking::with(['customer', 'bookedByUser', 'payments'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum);

        // Apply filter
        if ($filterKey) {
            [$type, $id] = explode('_', $filterKey, 2);
            if ($type === 'c') {
                $query->where('customer_id', $id);
            } elseif ($type === 'u') {
                $query->where('booked_by_user_id', $id);
            }
        }

        $bookings = $query->latest('booking_date')->get();

        // Group by composite key: "c_{customer_id}" for external, "u_{user_id}" for internal
        $customerSummary = $bookings->groupBy(function ($b) {
            return $b->customer_id
                ? 'c_' . $b->customer_id
                : 'u_' . ($b->booked_by_user_id ?? 'unknown');
        })->map(function ($group, $key) {
            $first = $group->first();
            $isInternal = !$first->customer_id;
            $verifiedBookings = $group->filter(
                fn($b) => $b->payments->where('verification_status', 'verified')->isNotEmpty()
            );
            return [
                'key'           => $key,
                'is_internal'   => $isInternal,
                'display_name'  => $isInternal
                    ? ($first->bookedByUser?->user_name ?? 'N/A')
                    : ($first->customer?->full_name ?? 'N/A'),
                'display_sub'   => $isInternal
                    ? ($first->bookedByUser ? ucfirst($first->bookedByUser->role) : null)
                    : ($first->customer?->phone ?? null),
                'booking_count'   => $group->count(),
                'completed_count' => $verifiedBookings->count(),
                'total_paid'      => $group->sum(
                    fn($b) => $b->payments->where('verification_status', 'verified')->sum('amount')
                ),
                'bookings'        => $group,
            ];
        })->sortByDesc('booking_count');

        $customers      = Customer::orderBy('full_name')->get();
        $staffUsers     = User::whereIn('role', ['admin', 'operation', 'accountant'])->orderBy('user_name')->get();
        $totalBookings  = $bookings->count();
        $totalPersons   = $customerSummary->count();
        $completedBookings = $bookings->filter(
            fn($b) => $b->payments->where('verification_status', 'verified')->isNotEmpty()
        )->count();

        return view('admin.reports.customer', compact(
            'month', 'customers', 'staffUsers', 'filterKey', 'customerSummary', 'bookings',
            'totalBookings', 'totalPersons', 'completedBookings'
        ));
    }

    public function customerReportPrint(Request $request)
    {
        $month     = $request->input('month', now()->format('Y-m'));
        $filterKey = $request->input('filter_key');
        [$year, $monthNum] = explode('-', $month);

        $query = Booking::with(['customer', 'bookedByUser', 'payments'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $monthNum);

        if ($filterKey) {
            [$type, $id] = explode('_', $filterKey, 2);
            if ($type === 'c') $query->where('customer_id', $id);
            elseif ($type === 'u') $query->where('booked_by_user_id', $id);
        }

        $bookings = $query->latest('booking_date')->get();

        $customerSummary = $bookings->groupBy(function ($b) {
            return $b->customer_id ? 'c_' . $b->customer_id : 'u_' . ($b->booked_by_user_id ?? 'unknown');
        })->map(function ($group, $key) {
            $first = $group->first();
            $isInternal = !$first->customer_id;
            $verifiedBookings = $group->filter(
                fn($b) => $b->payments->where('verification_status', 'verified')->isNotEmpty()
            );
            return [
                'key'             => $key,
                'is_internal'     => $isInternal,
                'display_name'    => $isInternal ? ($first->bookedByUser?->user_name ?? 'N/A') : ($first->customer?->full_name ?? 'N/A'),
                'display_sub'     => $isInternal ? ($first->bookedByUser ? ucfirst($first->bookedByUser->role) : null) : ($first->customer?->phone ?? null),
                'booking_count'   => $group->count(),
                'completed_count' => $verifiedBookings->count(),
                'total_paid'      => $group->sum(fn($b) => $b->payments->where('verification_status', 'verified')->sum('amount')),
                'bookings'        => $group,
            ];
        })->sortByDesc('booking_count');

        $totalBookings     = $bookings->count();
        $completedBookings = $bookings->filter(
            fn($b) => $b->payments->where('verification_status', 'verified')->isNotEmpty()
        )->count();

        return view('admin.reports.customer-print', compact(
            'month', 'filterKey', 'customerSummary', 'bookings',
            'totalBookings', 'completedBookings'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_type'    => 'required|in:salary,fuel,repair,other',
            'amount'          => 'required|numeric|min:0.01',
            'expense_date'    => 'required|date',
            'driver_id'       => 'nullable|exists:tbl_driver,driver_id',
            'truck_id'        => 'nullable|exists:tbl_truck,truck_id',
            'booking_id'      => 'nullable|exists:tbl_booking,booking_id',
            'driver_allowance'=> 'nullable|numeric|min:0',
            'description'     => 'nullable|string|max:255',
        ], [
            'expense_type.required' => 'សូមជ្រើសរើសប្រភេទចំណាយ។',
            'amount.required'       => 'សូមបញ្ចូលចំនួនទឹកប្រាក់។',
            'amount.numeric'        => 'ចំនួនទឹកប្រាក់មិនត្រឹមត្រូវ។',
            'expense_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទ។',
        ]);

        Expense::create($request->only([
            'expense_type', 'amount', 'driver_allowance', 'expense_date',
            'driver_id', 'truck_id', 'booking_id', 'description',
        ]));

        return back()->with('success', 'ចំណាយត្រូវបានកត់ត្រាដោយជោគជ័យ!');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_type'    => 'required|in:salary,fuel,repair,other',
            'amount'          => 'required|numeric|min:0.01',
            'expense_date'    => 'required|date',
            'driver_id'       => 'nullable|exists:tbl_driver,driver_id',
            'truck_id'        => 'nullable|exists:tbl_truck,truck_id',
            'booking_id'      => 'nullable|exists:tbl_booking,booking_id',
            'driver_allowance'=> 'nullable|numeric|min:0',
            'description'     => 'nullable|string|max:255',
        ], [
            'expense_type.required' => 'សូមជ្រើសរើសប្រភេទចំណាយ។',
            'amount.required'       => 'សូមបញ្ចូលចំនួនទឹកប្រាក់។',
            'amount.numeric'        => 'ចំនួនទឹកប្រាក់មិនត្រឹមត្រូវ។',
            'expense_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទ។',
        ]);

        $expense->update($request->only([
            'expense_type', 'amount', 'driver_allowance', 'expense_date',
            'driver_id', 'truck_id', 'booking_id', 'description',
        ]));

        return back()->with('success', 'ចំណាយត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'ចំណាយត្រូវបានលុប!');
    }
}
