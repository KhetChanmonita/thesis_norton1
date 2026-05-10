<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle login POST
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required'    => 'សូមបញ្ចូលឈ្មោះ ឬអ៊ីមែល។',
            'password.required' => 'សូមបញ្ចូលពាក្យសម្ងាត់។',
        ]);

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';

        $credentials = [
            $field     => $login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'ចូលគណនីបានជោគជ័យ!');
        }

        return back()->withErrors([
            'login' => 'ឈ្មោះអ្នកប្រើ ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ។',
        ])->withInput($request->except('password'));
    }

    // Show register form
    public function showRegisterForm()
    {
        return view('register');
    }

    // Handle register POST
    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string|min:3|max:100|unique:tbl_user,user_name',
            'phone'     => 'required|string|max:20',
            'password'  => 'required|string|min:8|confirmed',
            'email'     => 'nullable|email|unique:tbl_user,email',
        ], [
            'user_name.required' => 'សូមបញ្ចូលឈ្មោះអ្នកប្រើប្រាស់។',
            'user_name.min'      => 'ឈ្មោះត្រូវតែមានយ៉ាងហោចណាស់ ៣ តួអក្សរ។',
            'user_name.unique'   => 'ឈ្មោះអ្នកប្រើប្រាស់នេះមានរួចហើយ។',
            'phone.required'     => 'សូមបញ្ចូលលេខទូរស័ព្ទ។',
            'password.required'  => 'សូមបញ្ចូលពាក្យសម្ងាត់។',
            'password.min'       => 'ពាក្យសម្ងាត់ត្រូវតែមានយ៉ាងហោចណាស់ ៨ តួអក្សរ។',
            'password.confirmed' => 'ពាក្យសម្ងាត់មិនដូចគ្នា។',
            'email.unique'       => 'អ៊ីមែលនេះមានរួចហើយ។',
        ]);

        $user = User::create([
            'user_name' => $request->user_name,
            'phone'     => $request->phone,
            'email'     => $request->email ?? null,
            'password'  => Hash::make($request->password),
            'role'      => 'user',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'ចុះឈ្មោះបានជោគជ័យ! សូមស្វាគមន៍, ' . $user->user_name . '!');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'ចាកចេញបានជោគជ័យ!');
    }
}
