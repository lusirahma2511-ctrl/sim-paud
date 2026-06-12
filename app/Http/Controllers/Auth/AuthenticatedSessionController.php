<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create()
{
    return view('auth.login');
}

public function store(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->login)
            ->orWhere('nisn', $request->login)
            ->first();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Pastikan redirect berdasarkan role
protected function authenticated(Request $request, $user)
{
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'guru':
            return redirect()->route('guru.dashboard');  // ← Ini
        case 'kepala_sekolah':
            return redirect()->route('kepala.dashboard');
        case 'orang_tua':
            return redirect()->route('orangtua.dashboard');
        default:
            return redirect('/dashboard');
    }
}
}
