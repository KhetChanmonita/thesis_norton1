<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TruckAdminController extends Controller
{
    public function index()
    {
        $trucks = Truck::latest()->paginate(10);
        return view('admin.trucks.index', compact('trucks'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'truck_name'    => 'required|string|max:100',
            'plate_number'  => 'required|string|max:20|unique:tbl_truck,plate_number',
            'truck_size'    => 'nullable|string|max:50',
            'truck_color'   => 'nullable|string|max:50',
            'capacity_ton'  => 'nullable|numeric|min:0',
            'truck_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'plate_number.unique' => 'រថយន្តដែលមានស្លាកលេខនេះមានរួចហើយក្នុងប្រព័ន្ធ!',
        ]);

        if ($validator->fails()) {
            // Redirect explicitly to the trucks list — back() can land on whatever
            // admin page was last loaded (e.g. another tab), where no error banner exists.
            return redirect()->route('admin.trucks.index')
                             ->withErrors($validator)
                             ->withInput();
        }

        $data = $request->only('truck_name', 'plate_number', 'truck_size', 'truck_color', 'capacity_ton');

        if ($request->hasFile('truck_picture')) {
            $file = $request->file('truck_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/trucks'), $filename);
            $data['truck_picture'] = 'images/trucks/' . $filename;
        }

        Truck::create($data);

        return redirect()->route('admin.trucks.index')
                         ->with('success', 'រថយន្តបានបន្ថែមដោយជោគជ័យ!');
    }

    public function update(Request $request, Truck $truck)
    {
        $validator = Validator::make($request->all(), [
            'truck_name'    => 'required|string|max:100',
            'plate_number'  => 'required|string|max:20|unique:tbl_truck,plate_number,' . $truck->truck_id . ',truck_id',
            'truck_size'    => 'nullable|string|max:50',
            'truck_color'   => 'nullable|string|max:50',
            'capacity_ton'  => 'nullable|numeric|min:0',
            'truck_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'plate_number.unique' => 'រថយន្តដែលមានស្លាកលេខនេះមានរួចហើយក្នុងប្រព័ន្ធ!',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.trucks.index')
                             ->withErrors($validator)
                             ->withInput();
        }

        $data = $request->only('truck_name', 'plate_number', 'truck_size', 'truck_color', 'capacity_ton');

        if ($request->hasFile('truck_picture')) {
            // Delete old image safely
            if ($truck->truck_picture) {
                $oldPath = public_path($truck->truck_picture);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $file     = $request->file('truck_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/trucks'), $filename);
            $data['truck_picture'] = 'images/trucks/' . $filename;
        }

        $truck->update($data);

        // Always redirect to trucks list (back() can go to unexpected pages)
        return redirect()->route('admin.trucks.index')
                         ->with('success', 'ព័ត៌មានរថយន្តបានកែប្រែ!');
    }

    public function updateStatus(Request $request, Truck $truck)
    {
        $request->validate([
            'status' => 'required|in:available,in_progress,delivering,maintenance',
        ]);
        $truck->update(['status' => $request->status]);
        return redirect()->route('admin.trucks.index')
                         ->with('success', 'ស្ថានភាពរថយន្តបានផ្លាស់ប្ដូរ!');
    }

    public function destroy(Truck $truck)
    {
        if ($truck->truck_picture && file_exists(public_path($truck->truck_picture))) {
            unlink(public_path($truck->truck_picture));
        }
        $truck->delete();
        return redirect()->route('admin.trucks.index')
                         ->with('success', 'រថយន្តត្រូវបានលុប!');
    }
}
