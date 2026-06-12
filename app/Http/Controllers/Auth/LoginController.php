<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->login);

        $user = User::where('username', $loginInput)
                    ->orWhere('email', $loginInput)
                    ->first();

        if (!$user) {
            return back()->withErrors(['login' => 'Akun tidak ditemukan'])->withInput();
        }

        // Check if status column exists and if user is Nonaktif
        $hasStatusColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'status');
        if ($hasStatusColumn && $user->status === 'Nonaktif') {
            return back()->withErrors(['login' => 'Akun Anda dinonaktifkan'])->withInput();
        }

        // 🔐 SEMUA ROLE PAKAI HASH
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password salah']);
        }

        // LOGIN
        Auth::login($user);

        return $this->authenticated($request, $user);
    }

    protected function authenticated(Request $request, $user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'guru':
            case 'guru_kelas':
                return redirect()->route('guru.dashboard');
            case 'kepala_sekolah':
                return redirect()->route('kepala.dashboard');
            case 'orang_tua':
                return redirect()->route('orangtua.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}