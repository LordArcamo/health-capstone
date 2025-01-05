<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the home page with authentication status.
     */
    public function index()
    {
        // Passing the authentication status to the Home component
        return Inertia::render('Home', [
            'isAuthenticated' => auth()->check() // Check if the user is logged in
        ]);
    }

    /**
     * Display the login page.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
    
        // Redirect based on user role
        $user = Auth::user();
    
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard'); // Admin route
        } elseif ($user->role === 'doctor') {
            return redirect()->route('doctor.dashboard'); // Doctor route
        }
    
        return redirect()->route('dashboard'); // Default user dashboard
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
