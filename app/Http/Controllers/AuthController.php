<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menerima inputan dari form login.blade.php
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Cek role pengguna dan arahkan ke dashboard yang sesuai
            $user = Auth::user();
            $role = $user->role;
            if ($role === 'panitia') {
                if ($user->committeeMembers()->count() == 0) {
                    $userId = $user->id;
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    session(['registered_user_id' => $userId]);
                    return redirect('/pilih-event')->with('warning', 'Anda belum melengkapi data divisi. Silakan pilih Event & Divisi terlebih dahulu.');
                }
                return redirect()->intended('dashboard-panitia');
            } elseif ($role === 'admin') {
                return redirect()->intended('dashboard-admin');
            }

            // Default fallback
            return redirect()->intended('dashboard');
        }

        // Jika gagal login, kembali ke halaman login membawa pesan error
        return back()->withErrors([
            'email' => 'Email atau Password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Menerima inputan dari form register.blade.php
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255', 'unique:users',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->role === 'panitia' && !str_ends_with($value, '@mhs.unesa.ac.id')) {
                        $fail('Untuk role Panitia, Anda wajib menggunakan email mahasiswa (berakhiran @mhs.unesa.ac.id).');
                    }
                },
            ],
            'role' => ['required', 'in:panitia,admin,evaluator'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($user->role === 'panitia') {
            session(['registered_user_id' => $user->id]);
            return redirect('/pilih-event');
        }

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Fungsi Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}