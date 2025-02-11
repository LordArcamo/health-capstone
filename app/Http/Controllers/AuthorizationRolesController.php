<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AuthorizationRolesController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function admin()
    {

        $totalPatients = DB::table('consultation_details')->select('consultationDate as date')
        ->unionAll(
            DB::table('prenatal_consultation_details')->select('consultationDate as date')
        )
        ->unionAll(
            DB::table('national_immunization_programs')->select('created_at as date')
        )
        ->unionAll(
            DB::table('vaccination_records')->select('dateOfVisit as date')
        )
        ->count();

        // Debug: Check if we have any users
        $allUsers = User::all();
        \Log::info('All users count: ' . $allUsers->count());
        foreach ($allUsers as $user) {
            \Log::info('User:', [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'role' => $user->role,
                'email' => $user->email
            ]);
        }

        // Debug: Check distinct roles
        $roles = User::distinct()->pluck('role')->toArray();
        \Log::info('Available roles:', $roles);

        // Total users excluding 'admin' and 'user'
        $totalUsers = User::whereNotIn('role', ['admin', 'user'])->count();
        \Log::info('Total non-admin users: ' . $totalUsers);

        // Get user distribution data (excluding 'admin' and 'user')
        $distributionQuery = User::whereNotIn('role', ['admin', 'user'])
            ->select('role')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('role')
            ->orderBy('count', 'desc');

        $distributionData = $distributionQuery->get();
        \Log::info('Raw SQL:', [
            'query' => $distributionQuery->toSql(),
            'bindings' => $distributionQuery->getBindings()
        ]);
        \Log::info('Raw distribution data:', $distributionData->toArray());

        // Format the distribution data
        $formattedDistributionData = $distributionData->map(function ($item) {
            return [
                'role' => ucfirst($item->role), // Capitalize first letter
                'count' => (int)$item->count
            ];
        })->values()->toArray();

        // If no data, provide default structure
        if (empty($formattedDistributionData)) {
            \Log::warning('No data found, using defaults');
            $formattedDistributionData = [
                ['role' => 'Doctor', 'count' => 0],
                ['role' => 'Staff', 'count' => 0]
            ];
        }

        \Log::info('Final distribution data:', $formattedDistributionData);

        // Check all users and their roles
        $allUsers = User::all();
        \Log::info('All users in database:', $allUsers->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'role' => $user->role,
                'email' => $user->email
            ];
        })->toArray());

        // Monthly user data (excluding 'admin' and 'user')
        $monthlyUserData = User::whereNotIn('role', ['admin', 'user'])
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format monthly data to display month names
        $formattedMonthlyData = $monthlyUserData->map(function ($item) {
            return [
                'month' => Carbon::create($item->year, $item->month)->format('M'),
                'count' => (int)$item->count,
            ];
        });

        // Ensure all 12 months are present (fill missing months with 0)
        $allMonths = collect(range(1, 12))->map(function ($month) use ($formattedMonthlyData) {
            $monthName = Carbon::create()->month($month)->format('M');
            $data = $formattedMonthlyData->firstWhere('month', $monthName);

            return [
                'month' => $monthName,
                'count' => $data ? (int)$data['count'] : 0,
            ];
        });

        $responseData = [
            'pageTitle' => 'Admin Dashboard',
            'user' => auth()->user(),
            'totalUsers' => $totalUsers,
            'totalPatients' => $totalPatients,
            'countUsers' => $allMonths->toArray(),
            'staffDistributionData' => $formattedDistributionData
        ];

        \Log::info('Response data:', $responseData);

        return Inertia::render('Admin/AdminDashboard', $responseData);
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
