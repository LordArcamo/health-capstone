<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use App\Models\ConsultationDetails;

class DoctorDashboardController extends Controller
{
    /**
     * Display the main doctor dashboard with static data.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $today = now()->toDateString(); // e.g., "2025-01-06"
                // Fetch general consultation details
                $generalConsultations = DB::table('personal_information')
                ->join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
                ->whereDate('consultation_details.consultationDate', $today) // Filter for today's date
                ->select(
                    'personal_information.personalId',
                    'personal_information.firstName',
                    'personal_information.lastName',
                    'personal_information.middleName',
                    'personal_information.suffix',
                    'personal_information.purok',
                    'personal_information.barangay',
                    'personal_information.age',
                    'personal_information.birthdate',
                    'personal_information.contact',
                    'personal_information.sex',
                    'consultation_details.consultationDate',
                    'consultation_details.consultationTime',
                    'consultation_details.modeOfTransaction',
                    'consultation_details.bloodPressure',
                    'consultation_details.temperature',
                    'consultation_details.height',
                    'consultation_details.weight',
                    'consultation_details.visitType', // Include visitType directly here
                    DB::raw('NULL as providerName'), // Placeholder for missing column
                    DB::raw('NULL as nameOfSpouse'), // Placeholder for missing column
                    DB::raw('NULL as emergencyContact'), // Placeholder for missing column
                    DB::raw('NULL as fourMember'), // Placeholder for missing column
                    DB::raw('NULL as philhealthStatus'), // Placeholder for missing column
                    DB::raw('NULL as philhealthNo'), // Placeholder for missing column
                    DB::raw("'General Checkup' as checkupType"), // Static checkup type
                    DB::raw("'General' as consultationType") // Type identifier
                );

            // Fetch prenatal consultation details for today
            $prenatalConsultations = DB::table('personal_information')
                ->join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
                ->whereDate('prenatal_consultation_details.consultationDate', $today) // Filter for today's date
                ->select(
                    'personal_information.personalId',
                    'personal_information.firstName',
                    'personal_information.lastName',
                    'personal_information.middleName',
                    'personal_information.suffix',
                    'personal_information.purok',
                    'personal_information.barangay',
                    'personal_information.age',
                    'personal_information.birthdate',
                    'personal_information.contact',
                    'personal_information.sex',
                    'prenatal_consultation_details.consultationDate',
                    'prenatal_consultation_details.consultationTime',
                    'prenatal_consultation_details.modeOfTransaction',
                    'prenatal_consultation_details.bloodPressure',
                    'prenatal_consultation_details.temperature',
                    'prenatal_consultation_details.height',
                    'prenatal_consultation_details.weight',
                    DB::raw("'Prenatal' as visitType"), // Set visitType to "Prenatal"
                    'prenatal_consultation_details.providerName',
                    'prenatal_consultation_details.nameOfSpouse',
                    'prenatal_consultation_details.emergencyContact',
                    'prenatal_consultation_details.fourMember',
                    'prenatal_consultation_details.philhealthStatus',
                    'prenatal_consultation_details.philhealthNo',
                    DB::raw("'Prenatal Checkup' as checkupType"), // Static checkup type
                    DB::raw("'Prenatal' as consultationType") // Type identifier
                );

            // Combine both queries using union
            $data = $generalConsultations->union($prenatalConsultations)->get();

        return Inertia::render('DoctorDashboard', [
            'totalPatients' => 1200,
            'ITRConsultation' => $data,
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
