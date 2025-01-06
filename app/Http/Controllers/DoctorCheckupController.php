<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use App\Models\PersonalInformation;
use App\Models\ConsultationDetail;

class DoctorCheckupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the current date
        $today = now()->toDateString(); // e.g., "2025-01-06"

        // Fetch general consultation details for today
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

        // Pass the data to Inertia
        return Inertia::render('DoctorCheckup', [
            'ITRConsultation' => $data,
        ]);
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
