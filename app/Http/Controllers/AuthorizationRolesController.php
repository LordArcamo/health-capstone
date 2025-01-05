<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthorizationRolesController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function admin()
    {
        // Pass data to the Inertia view
        return Inertia::render('AdminDashboard', [
            'pageTitle' => 'Admin Dashboard',
            'user' => auth()->user(), // Optional: Pass authenticated user data
        ]);
    }

    public function doctor()
    {
        // Pass data to the Inertia view
        return Inertia::render('DoctorDashboard', [
            'pageTitle' => 'Doctor Dashboard',
            'user' => auth()->user(), // Optional: Pass authenticated user data
        ]);
    }
}
