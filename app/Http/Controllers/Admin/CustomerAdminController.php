<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerAdminController extends Controller
{
    public function index()
    {
        $customers = Customer::withCount('bookings')->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:tbl_customer,email,' . $customer->customer_id . ',customer_id',
            'phone'        => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:500',
        ]);

        $customer->update($request->only(['full_name', 'email', 'phone', 'company_name', 'address']));

        return redirect()->route('admin.customers.index')
            ->with('success', 'ព័ត៌មានអតិថិជនត្រូវបានធ្វើបច្ចុប្បន្នភាព!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'អតិថិជនត្រូវបានលុប!');
    }
}
