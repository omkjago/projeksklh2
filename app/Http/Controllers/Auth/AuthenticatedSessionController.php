<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // Authentication passed...
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard-admin');
            } elseif ($user->role === 'guru') {
                return redirect()->route('dashboard-guru');
            } elseif ($user->role === 'siswa') {
                return redirect()->route('dashboard-siswa');
            }elseif ($user->role === 'orangtua') {
                return redirect()->route('dashboard-orangtua');
            } else {
                // Jika role tidak dikenali, redirect ke halaman default
                return redirect()->intended('login');
            }
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
