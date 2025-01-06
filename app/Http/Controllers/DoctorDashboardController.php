<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    /**
     * Display the main doctor dashboard with static data.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('DoctorDashboard', [
            'totalPatients' => 1200,
            'patientsInQueue' => [
                ['name' => 'John Doe', 'age' => 34, 'reason' => 'Fever'],
                ['name' => 'Jane Smith', 'age' => 29, 'reason' => 'Cough'],
            ],
            'latestPatients' => [
                ['name' => 'Alice Johnson', 'checkInTime' => '10:00 AM', 'diagnosis' => 'Common Cold'],
                ['name' => 'Robert Brown', 'checkInTime' => '10:15 AM', 'diagnosis' => 'Allergy'],
            ],
            'todayAppointments' => 15,
            'criticalCases' => 3,
            'notifications' => [
                ['message' => 'Emergency case in Room 4.'],
                ['message' => 'Patient John Doe requires immediate attention.'],
            ],
        ]);
    }

    /**
     * Display a tailored doctor dashboard with user data.
     *
     * @return \Inertia\Response
     */
    public function doctor()
    {
        return Inertia::render('DoctorDashboard', [
            'pageTitle' => 'Doctor Dashboard',
            'user' => auth()->user(),
        ]);
    }

    public function checkup()
    {
        // Replace with actual database query to fetch the next patient in the queue
        $patientsQueue = [
            // Example: No patients in the queue
            // ['id' => 1, 'name' => 'John Doe', 'age' => 34, 'reason' => 'Fever'],
        ];

        $nextPatient = $patientsQueue[0] ?? null;

        if (!$nextPatient) {
            return Inertia::render('DoctorCheckup', [
                'patient' => null,
                'message' => 'No patients are currently in the checkup queue.',
            ]);
        }

        return Inertia::render('DoctorCheckup', [
            'patient' => $nextPatient,
        ]);
    }

    
}
