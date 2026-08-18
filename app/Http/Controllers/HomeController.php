<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Truck;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $trucks = Truck::all();
            if ($trucks->isEmpty()) {
                $trucks = collect([]);
            }
            return view('home', compact('trucks'));
        } catch (\Exception $e) {
            $trucks = collect([]);
            return view('home', compact('trucks'));
        }
    }

    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function login(Request $request)
    {
        return app(AuthController::class)->login($request);
    }

    public function register(Request $request)
    {
        return app(AuthController::class)->register($request);
    }

    public function logout(Request $request)
    {
        return app(AuthController::class)->logout($request);
    }

    public function price()
    {
        $rates = ShippingRate::all();

        // Keyed by [type][origin][province_en] for the JS calculator
        $ratesJson = [];
        foreach ($rates as $r) {
            $ratesJson[$r->type][$r->origin][$r->province_name_en] = [
                'km'         => $r->province_name_km,
                'base_price' => $r->base_price,
            ];
        }

        $provinces = ShippingRate::provinces();

        return view('price', compact('ratesJson', 'provinces'));
    }

    public function import()
    {
        return view('home');
    }

    public function export()
    {
        return view('home');
    }

    public function history()
    {
        $user       = Auth::user();
        $sessionIds = array_unique(session('my_booking_ids', []));

        $query = \App\Models\Booking::with(['customer', 'payments']);

        if ($user->phone && !empty($sessionIds)) {
            $phone = $user->phone;
            $query->where(function ($q) use ($phone, $sessionIds) {
                $q->whereHas('customer', fn ($q2) => $q2->where('phone', $phone))
                  ->orWhereIn('booking_id', $sessionIds);
            });
        } elseif ($user->phone) {
            $phone = $user->phone;
            $query->whereHas('customer', fn ($q) => $q->where('phone', $phone));
        } elseif (!empty($sessionIds)) {
            $query->whereIn('booking_id', $sessionIds);
        } else {
            return view('history', ['bookings' => collect(), 'totalPaid' => 0, 'pendingCount' => 0]);
        }

        $bookings     = $query->latest()->get();
        $totalPaid    = $bookings->flatMap->payments->filter(fn($p) => $p->verification_status !== 'rejected')->sum('amount');
        $pendingCount = $bookings->whereIn('payment_status', ['unpaid', 'deposit_paid'])
                                 ->where('status', '!=', 'cancelled')->count();

        return view('history', compact('bookings', 'totalPaid', 'pendingCount'));
    }

    public function profile()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return view('admin.profile', compact('user'));
        }
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'user_name'       => 'required|string|max:100',
            'email'           => 'required|email|unique:tbl_user,email,' . $user->user_id . ',user_id',
            'phone'           => 'nullable|string|max:20',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = [
            'user_name.required'       => 'សូមបញ្ចូលឈ្មោះ',
            'email.required'           => 'សូមបញ្ចូលអ៊ីមែល',
            'email.email'              => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវ',
            'email.unique'             => 'អ៊ីមែលនេះបានប្រើប្រាស់រួចហើយ',
            'profile_picture.image'    => 'ឯកសារត្រូវតែជារូបភាព',
            'profile_picture.mimes'    => 'រូបភាពត្រូវតែជា jpg, jpeg, png, webp',
            'profile_picture.max'      => 'រូបភាពមិនត្រូវលើស 2MB',
        ];

        if ($request->filled('new_password')) {
            $rules['current_password']     = 'required';
            $rules['new_password']         = 'required|min:8|confirmed';
            $messages['current_password.required'] = 'សូមបញ្ចូលពាក្យសម្ងាត់បច្ចុប្បន្ន';
            $messages['new_password.min']           = 'ពាក្យសម្ងាត់ថ្មីត្រូវមានយ៉ាងហោចណាស់ ៨ តួ';
            $messages['new_password.confirmed']     = 'ការបញ្ជាក់ពាក្យសម្ងាត់មិនត្រូវគ្នា';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('profile')
                             ->withErrors($validator)
                             ->withInput();
        }

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->route('profile')
                                 ->withErrors(['current_password' => 'ពាក្យសម្ងាត់បច្ចុប្បន្នមិនត្រឹមត្រូវ'])
                                 ->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
                unlink(public_path($user->profile_picture));
            }
            $file     = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/users'), $filename);
            $user->profile_picture = 'images/users/' . $filename;
        }

        $user->user_name = $request->user_name;
        $user->email     = $request->email;
        $user->phone     = $request->phone;
        $user->save();

        return redirect()->route('profile')->with('success', 'ព័ត៌មានគណនីបានកែប្រែដោយជោគជ័យ!');
    }

    public function sendProfilePasswordReset()
    {
        $user  = Auth::user();
        $email = $user->email;

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        $token    = Str::random(64);
        $resetUrl = route('password.reset', $token) . '?email=' . urlencode($email);

        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        try {
            Mail::to($email)->send(new PasswordResetMail($resetUrl, $user->user_name));
        } catch (\Throwable $e) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            \Illuminate\Support\Facades\Log::error('Password reset mail failed: ' . $e->getMessage());
            return redirect()->route('profile')
                             ->with('reset_error', 'មិនអាចផ្ញើអ៊ីមែលបាន។ សូមពិនិត្យការតភ្ជាប់អ៊ីនធឺណិត ឬការកំណត់ SMTP រួចព្យាយាមម្ដងទៀត។');
        }

        return redirect()->route('profile')
                         ->with('reset_sent', 'តំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ត្រូវបានផ្ញើទៅ ' . $email . '។ សូមពិនិត្យប្រអប់ចូល (inbox) ឬ spam folder។');
    }

    public function settings()
    {
        return view('home');
    }
}
