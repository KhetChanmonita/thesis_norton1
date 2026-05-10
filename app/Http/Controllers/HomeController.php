<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Truck;

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
        return view('price', ['booking' => null]);
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
        return view('home');
    }

    public function profile()
    {
        return view('home');
    }

    public function settings()
    {
        return view('home');
    }
}
