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
            ->distinct()
            ->get();
        return Inertia::render('Table/NationalImmunization', [
            'IMMUNIZATION' => $data, 

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalId = $request->get('patient_personalId');

        if ($personalId === 'new') {
            return Inertia::render('CheckUp/NationalImmunizationCheckup', [
                'personalInfo' => null, // No existing patient
            ]);
        }

        $personalInfo = $personalId ? PersonalInformation::find($personalId) : null;

        if (!$personalInfo) {
            return back()->withErrors(['error' => 'Patient not found']);
        }

        return Inertia::render('CheckUp/NationalImmunizationCheckup', [
            'personalInfo' => $personalInfo,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log the incoming request
        \Log::info('Incoming request data:', $request->all());

        // Validate request data
        $validatedData = $request->validate([
            // Personal Information
            'personalId' => 'nullable|exists:personal_information,personalId',
            'firstName' => 'required_without:personalId|string|max:100',
            'lastName' => 'required_without:personalId|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'purok' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'age' => 'nullable|numeric',
            'birthdate' => 'nullable|date',
            'contact' => 'nullable|string|max:15',
            'sex' => 'nullable|string|max:10',

            // Additional ITR Data
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

        $personalInfo = null;

        // Check if the patient exists
        if (isset($validatedData['personalId'])) {
            $personalInfo = PersonalInformation::find($validatedData['personalId']);

            if ($personalInfo) {
                // Update existing personal information
                $personalInfo->update([
                    'firstName' => $validatedData['firstName'] ?? $personalInfo->firstName,
                    'lastName' => $validatedData['lastName'] ?? $personalInfo->lastName,
                    'middleName' => $validatedData['middleName'] ?? $personalInfo->middleName,
                    'suffix' => $validatedData['suffix'] ?? $personalInfo->suffix,
                    'purok' => $validatedData['purok'] ?? $personalInfo->purok,
                    'barangay' => $validatedData['barangay'] ?? $personalInfo->barangay,
                    'age' => $validatedData['age'] ?? $personalInfo->age,
                    'birthdate' => $validatedData['birthdate'] ?? $personalInfo->birthdate,
                    'contact' => $validatedData['contact'] ?? $personalInfo->contact,
                    'sex' => $validatedData['sex'] ?? $personalInfo->sex,
                ]);
            } else {
                return back()->withErrors(['error' => 'Patient not found.']);
            }
        } else {
            // Create new personal information
            $personalInfo = PersonalInformation::create([
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
            ]);
        }

        // Save the immunization data
        NationalImmunizationProgram::create([
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
        ]);

        // Log success and return response
        \Log::info('Data saved successfully for personalId:', [
            'personalId' => $personalInfo->personalId,
        ]);

        return back()->with([
            'success' => 'Data saved successfully!',
            'personalId' => $personalInfo->personalId,
        ]);
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
