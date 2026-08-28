<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    // ── Login with email + password ──────────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'សូមបញ្ចូលអ៊ីមែល។',
            'email.email'    => 'អ៊ីមែលមិនត្រឹមត្រូវ។',
        ]);

        if (Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $role = Auth::user()->role;
            if (in_array($role, ['admin', 'operation', 'accountant', 'driver'])) {
                return redirect()->route('admin.dashboard')->with('success', 'ចូលគណនីបានជោគជ័យ!');
            }
            return redirect()->intended('/')->with('success', 'ចូលគណនីបានជោគជ័យ!');
        }

        return back()->withErrors([
            'email' => 'អ៊ីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ។',
        ])->withInput($request->except('password'));
    }

    // ── Register ─────────────────────────────────────────────────────────────
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|min:3|max:100',
            'email'     => 'required|email|max:191|unique:tbl_user,email',
            'password'  => 'required|string|min:8|confirmed',
        ], [
            'user_name.required' => 'សូមបញ្ចូលឈ្មោះអ្នកប្រើប្រាស់។',
            'user_name.min'      => 'ឈ្មោះត្រូវតែមានយ៉ាងហោចណាស់ ៣ តួអក្សរ។',
            'email.required'     => 'សូមបញ្ចូលអ៊ីមែល។',
            'email.email'        => 'អ៊ីមែលមិនត្រឹមត្រូវ។',
            'email.unique'       => 'អ៊ីមែលនេះមានរួចហើយ។',
            'password.required'  => 'សូមបញ្ចូលពាក្យសម្ងាត់។',
            'password.min'       => 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ ៨ តួអក្សរ។',
            'password.confirmed' => 'ពាក្យសម្ងាត់មិនដូចគ្នា។',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'user_name' => $request->user_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'ចុះឈ្មោះបានជោគជ័យ! សូមស្វាគមន៍, ' . $user->user_name . '!');
    }

    // ── Logout ───────────────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'ចាកចេញបានជោគជ័យ!');
    }

    // ── Forgot password — show form ──────────────────────────────────────────
    public function showForgotPassword()
    {
        return view('forgot-password');
    }

    // ── Forgot password — send reset link ────────────────────────────────────
    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'សូមបញ្ចូលអ៊ីមែល។',
            'email.email'    => 'អ៊ីមែលមិនត្រឹមត្រូវ។',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'មិនរកឃើញអ្នកប្រើប្រាស់ដែលមានអ៊ីមែលនេះ។',
            ]);
        }

        // Remove any existing token for this email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Generate a secure token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = route('password.reset', $token) . '?email=' . urlencode($request->email);

        try {
            Mail::to($request->email)->send(new PasswordResetMail($resetUrl, $user->user_name));
        } catch (\Throwable $e) {
            // Clean up the token so the user can try again
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            \Illuminate\Support\Facades\Log::error('Password reset mail failed: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'មិនអាចផ្ញើអ៊ីមែលបាន។ សូមពិនិត្យការតភ្ជាប់អ៊ីនធឺណិត ឬការកំណត់ SMTP រួចព្យាយាមម្តងទៀត។',
            ]);
        }

        return back()->with('status', 'តំណភ្ជាប់កំណត់ពាក្យសម្ងាត់ត្រូវបានផ្ញើទៅ ' . $request->email . '។ សូមពិនិត្យប្រអប់ចូល (inbox) ឬ spam folder។');
    }

    // ── Reset password — show form ───────────────────────────────────────────
    public function showResetPassword(Request $request, string $token)
    {
        return view('reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    // ── Reset password — process ─────────────────────────────────────────────
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ ៨ តួអក្សរ។',
            'password.confirmed' => 'ពាក្យសម្ងាត់មិនដូចគ្នា។',
        ]);

        $record = DB::table('password_reset_tokens')
                     ->where('email', $request->email)
                     ->orderByDesc('created_at')
                     ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'តំណភ្ជាប់មិនត្រឹមត្រូវ ឬបានផុតកំណត់។ សូមស្នើសុំម្តងទៀត។']);
        }

        // Token expires after 60 minutes
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'តំណភ្ជាប់បានផុតកំណត់ (ត្រឹម ៦០ នាទី)។ សូមស្នើសុំម្តងទៀត។']);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'មិនរកឃើញអ្នកប្រើប្រាស់។']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
                         ->with('success', 'ពាក្យសម្ងាត់ត្រូវបានកំណត់ឡើងវិញ! សូមចូលគណនីម្តងទៀត។');
    }
}
