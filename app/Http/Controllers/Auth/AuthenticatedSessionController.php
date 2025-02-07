<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        // return redirect()->route('dashboard')->with('success', 'Login sebagai Admin Berhasil');
        return redirect()->route('lobby')->with('success', 'Login Berhasil');

    }

    // If login fails, show an error using withErrors() and custom message using with()
    return back()
        ->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])
        ->with('error', 'Login Gagal'); // Flash message for SweetAlert2 or other use
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
