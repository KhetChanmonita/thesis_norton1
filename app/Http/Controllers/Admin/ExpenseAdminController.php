<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Truck;
use Illuminate\Http\Request;

class ExpenseAdminController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
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

        $grandTotal = $totals->sum();

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

            $trendData[] = [
                'label'  => $m->format('m/Y'),
                'salary' => (float) ($sums['salary'] ?? 0),
                'fuel'   => (float) ($sums['fuel'] ?? 0),
                'repair' => (float) ($sums['repair'] ?? 0),
                'other'  => (float) ($sums['other'] ?? 0),
            ];
        }

        $drivers = Driver::orderBy('full_name')->get();
        $trucks  = Truck::orderBy('truck_name')->get();

        return view('admin.reports.index', compact(
            'expenses', 'month', 'totals', 'grandTotal', 'drivers', 'trucks', 'trendData'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_type' => 'required|in:salary,fuel,repair,other',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'driver_id'    => 'nullable|exists:tbl_driver,driver_id',
            'truck_id'     => 'nullable|exists:tbl_truck,truck_id',
            'description'  => 'nullable|string|max:255',
        ], [
            'expense_type.required' => 'សូមជ្រើសរើសប្រភេទចំណាយ។',
            'amount.required'       => 'សូមបញ្ចូលចំនួនទឹកប្រាក់។',
            'amount.numeric'        => 'ចំនួនទឹកប្រាក់មិនត្រឹមត្រូវ។',
            'expense_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទ។',
        ]);

        Expense::create($request->only([
            'expense_type', 'amount', 'expense_date', 'driver_id', 'truck_id', 'description',
        ]));

        return back()->with('success', 'ចំណាយត្រូវបានកត់ត្រាដោយជោគជ័យ!');
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_type' => 'required|in:salary,fuel,repair,other',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'driver_id'    => 'nullable|exists:tbl_driver,driver_id',
            'truck_id'     => 'nullable|exists:tbl_truck,truck_id',
            'description'  => 'nullable|string|max:255',
        ], [
            'expense_type.required' => 'សូមជ្រើសរើសប្រភេទចំណាយ។',
            'amount.required'       => 'សូមបញ្ចូលចំនួនទឹកប្រាក់។',
            'amount.numeric'        => 'ចំនួនទឹកប្រាក់មិនត្រឹមត្រូវ។',
            'expense_date.required' => 'សូមជ្រើសរើសកាលបរិច្ឆេទ។',
        ]);

        $expense->update($request->only([
            'expense_type', 'amount', 'expense_date', 'driver_id', 'truck_id', 'description',
        ]));

        return back()->with('success', 'ចំណាយត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'ចំណាយត្រូវបានលុប!');
    }
}
