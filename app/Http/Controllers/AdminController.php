<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Pass data to the Inertia view
        return Inertia::render('AdminDashboard', [
            'pageTitle' => 'Admin Dashboard',
            'user' => auth()->user(), // Optional: Pass authenticated user data
        ]);
    }
}
