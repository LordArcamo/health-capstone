<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class AuthorizationRolesController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function admin()
    {
        $totalUsers = User::where('role', '!=', 'admin')->count();
        // Pass data to the Inertia view
        return Inertia::render('Admin/AdminDashboard', [
            'pageTitle' => 'Admin Dashboard',
            'user' => auth()->user(), // Optional: Pass authenticated user data
            'totalUsers' => $totalUsers,
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

    //     public function index()
    // {
    //     // Count all users excluding the admin role
    //     $totalUsers = User::where('role', '!=', 'admin')->count();

    //     // Pass the user count to the Inertia view
    //     return Inertia::render('Admin/AdminDashboard', [
    //         'totalUsers' => $totalUsers,
    //     ]);
    // }
}
