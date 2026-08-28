<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('driver')->where('role', '!=', 'user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('user_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users   = $query->latest()->paginate(15)->withQueryString();
        $drivers = Driver::orderBy('full_name')->get();

        $roleCounts = User::where('role', '!=', 'user')
            ->selectRaw('role, count(*) as cnt')
            ->groupBy('role')
            ->pluck('cnt', 'role');

        return view('admin.users.index', compact('users', 'drivers', 'roleCounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:tbl_user,email',
            'password'  => 'required|string|min:8',
            'role'      => 'required|in:admin,operation,accountant,driver',
            'phone'     => 'nullable|string|max:20',
            'driver_id' => 'nullable|exists:tbl_driver,driver_id',
        ], [
            'user_name.required' => 'សូមបញ្ចូលឈ្មោះ។',
            'email.required'     => 'សូមបញ្ចូលអ៊ីមែល។',
            'email.unique'       => 'អ៊ីមែលនេះមានរួចហើយ។',
            'password.required'  => 'សូមបញ្ចូលពាក្យសម្ងាត់។',
            'password.min'       => 'ពាក្យសម្ងាត់យ៉ាងតិច ៨ តួអក្សរ។',
            'role.required'      => 'សូមជ្រើសរើសសិទ្ធិ។',
        ]);

        User::create([
            'user_name' => $request->user_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'phone'     => $request->phone,
            'driver_id' => $request->role === 'driver' ? $request->driver_id : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'គណនីត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'user_name' => 'required|string|max:100',
            'email'     => 'required|email|unique:tbl_user,email,' . $user->user_id . ',user_id',
            'role'      => 'required|in:admin,operation,accountant,driver',
            'phone'     => 'nullable|string|max:20',
            'driver_id' => 'nullable|exists:tbl_driver,driver_id',
            'password'  => 'nullable|string|min:8',
        ], [
            'email.unique'  => 'អ៊ីមែលនេះមានរួចហើយ។',
            'password.min'  => 'ពាក្យសម្ងាត់យ៉ាងតិច ៨ តួអក្សរ។',
        ]);

        $data = [
            'user_name' => $request->user_name,
            'email'     => $request->email,
            'role'      => $request->role,
            'phone'     => $request->phone,
            'driver_id' => $request->role === 'driver' ? $request->driver_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'គណនីត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function destroy(User $user)
    {
        if ($user->user_id === auth()->id()) {
            return back()->with('error', 'មិនអាចលុបគណនីខ្លួនឯងបានទេ!');
        }
        $user->delete();
        return back()->with('success', 'គណនីត្រូវបានលុប!');
    }
}
