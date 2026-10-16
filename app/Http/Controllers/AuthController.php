<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth_login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak cocok dengan data staf RSI Kendal.',
        ])->onlyInput('email');
    }

    public function quickLogin()
    {
        // Fitur kemudahan demonstrasi sidang tugas akhir: login instan sebagai dokter spesialis
        $doctor = User::where('role', 'like', '%Dokter%')->first();
        if (!$doctor) {
            $doctor = User::first();
        }

        if ($doctor) {
            Auth::login($doctor);
            request()->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Berhasil masuk dalam mode demonstrasi klinis: ' . $doctor->name);
        }

        return redirect()->route('login')->withErrors(['email' => 'Akun demonstrasi belum diinisialisasi melalui database seeder.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('skrining.index')->with('info', 'Anda telah keluar dari sesi staf medis.');
    }
}
