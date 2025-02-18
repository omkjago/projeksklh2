<?php

// app/Http/Middleware/CheckRole.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Jika pengguna tidak terautentikasi atau tidak memiliki role yang sesuai
            return redirect('/home');
        }

        return $next($request);
    }
}
