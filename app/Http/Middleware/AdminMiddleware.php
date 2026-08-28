<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    // Roles that can access the admin panel
    const PANEL_ROLES = ['admin', 'operation', 'accountant', 'driver'];

    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::check() || !in_array(Auth::user()->role, self::PANEL_ROLES)) {
            return redirect()->route('home')->with('error', 'អ្នកមិនមានសិទ្ធិចូលទំព័រនេះទេ។');
        }
        return $next($request);
    }
}
