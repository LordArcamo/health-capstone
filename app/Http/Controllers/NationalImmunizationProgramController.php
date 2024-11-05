<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\NationalImmunizationProgram;
use App\Models\PersonalInformation;

class NationalImmunizationProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = PersonalInformation::join('national_immunization_programs', 'personal_information.personalId', '=', 'national_immunization_programs.personalId')
            ->select(
                'personal_information.*',
                'national_immunization_programs.birthplace',
                'national_immunization_programs.bloodtype',
                'national_immunization_programs.mothername',
                'national_immunization_programs.dswdNhts',
                'national_immunization_programs.facilityHouseholdno',
                'national_immunization_programs.houseHoldno',
                'national_immunization_programs.fourpsmember',
                'national_immunization_programs.PCBMember',
                'national_immunization_programs.philhealthMember',
                'national_immunization_programs.statusType',
                'national_immunization_programs.philhealthNo',
                'national_immunization_programs.ifMember',
                'national_immunization_programs.familyMember',
                'national_immunization_programs.ttStatus',
                'national_immunization_programs.dateAssesed',
                'national_immunization_programs.date',
                'national_immunization_programs.place',
                'national_immunization_programs.guardian',
            )
            ->get();
        return Inertia::render('Table/NationalImmunization', [
            'IMMUNIZATION' => $data, 

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CheckUp/NationalImmunizationCheckup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'middleName' => 'required|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'purok' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'age' => 'required|numeric',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:15',
            'sex' => 'required|string|max:10',
            'birthplace' => 'required|string|max:100',
            'bloodtype' => 'required|string|max:5',
            'mothername' => 'required|string|max:100',
            'dswdNhts' => 'required|string|max:100',
            'facilityHouseholdno' => 'required|string|max:100',
            'houseHoldno' => 'required|string|max:100',
            'fourpsmember' => 'required|string|max:100',
            'PCBMember' => 'required|string|max:100',
            'philhealthMember' => 'required|string|max:100',
            'statusType' => 'required|string|max:50',
            'philhealthNo' => 'required|string|max:15',
            'ifMember' => 'required|string|max:100',
            'familyMember' => 'required|string|max:100',
            'ttstatus' => 'required|string|max:10',
            'dateAssesed' => 'required|date',
            'date' => 'required|date',
            'place' => 'required|string|max:100',
            'guardian' => 'required|string|max:100',
        ]);

        // Split validated data for PersonalInformation
        $personalData = [
            'firstName' => $validatedData['firstName'],
            'lastName' => $validatedData['lastName'],
            'middleName' => $validatedData['middleName'],
            'suffix' => $validatedData['suffix'],
            'purok' => $validatedData['purok'],
            'barangay' => $validatedData['barangay'],
            'age' => $validatedData['age'],
            'birthdate' => $validatedData['birthdate'],
            'contact' => $validatedData['contact'],
            'sex' => $validatedData['sex'],
        ];

        // Save personal data to PersonalInformation table
        $personalInfo = PersonalInformation::create($personalData);

        // Prepare data for NationalImmunizationProgram table
        $immunizationData = [
            'personalId' => $personalInfo->personalId, // Link to personal information record
            'birthplace' => $validatedData['birthplace'],
            'bloodtype' => $validatedData['bloodtype'],
            'mothername' => $validatedData['mothername'],
            'dswdNhts' => $validatedData['dswdNhts'],
            'facilityHouseholdno' => $validatedData['facilityHouseholdno'],
            'houseHoldno' => $validatedData['houseHoldno'],
            'fourpsmember' => $validatedData['fourpsmember'],
            'PCBMember' => $validatedData['PCBMember'],
            'philhealthMember' => $validatedData['philhealthMember'],
            'statusType' => $validatedData['statusType'],
            'philhealthNo' => $validatedData['philhealthNo'],
            'ifMember' => $validatedData['ifMember'],
            'familyMember' => $validatedData['familyMember'],
            'ttStatus' => $validatedData['ttstatus'],
            'dateAssesed' => $validatedData['dateAssesed'],
            'date' => $validatedData['date'],
            'place' => $validatedData['place'],
            'guardian' => $validatedData['guardian'],
        ];

        // Save immunization data to NationalImmunizationProgram table
        $immunization = NationalImmunizationProgram::create($immunizationData);

        // Redirect back with a success message
        return back()->with('Success', 'Data saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Logic to show a specific resource
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Logic to show edit form for a specific resource
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logic to update a specific resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Logic to delete a specific resource
    }
}
