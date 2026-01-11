<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * FORM REGISTER
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * PROSES REGISTER USER BARU
     */
    public function registerStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6', // Hapus kata 'confirmed'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
    }

    /**
     * FORM LOGIN
     */
    public function login()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('user.dashboard');
        }
        return view('auth.login');
    }


    /**
     * PROSES LOGIN
     */
    public function loginStore(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            // FIX: regenerate session biar csrf tidak expired
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    /**
     * LOGOUT
     */
    public function logout()
    {
        Auth::logout();
        return redirect('/login'); // arahkan ke halaman login
    }
}
