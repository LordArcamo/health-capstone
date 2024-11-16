<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PreNatal;
use App\Models\PersonalInformation;

class PreNatalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PersonalInformation::join('prenatal', 'personal_information.personalId', '=', 'prenatal.personalId')
        ->select(
            'prenatal.prenatalId',
            'personal_information.*', 
            'prenatal.modeOfTransaction',
            'prenatal.consultationDate',
            'prenatal.consultationTime',
            'prenatal.bloodPressure',
            'prenatal.temperature',
            'prenatal.height', 
            'prenatal.weight', 
            'prenatal.providerName', 
            'prenatal.nameOfSpouse',
            'prenatal.emergencyContact', 
            'prenatal.fourMember', 
            'prenatal.philhealthStatus', 
            'prenatal.philhealthId',
            'prenatal.menarche', 
            'prenatal.sexualOnset', 
            'prenatal.periodDuration', 
            'prenatal.birthControl', 
            'prenatal.intervalCycle',
            'prenatal.menopause', 
            'prenatal.lmp', 
            'prenatal.edc', 
            'prenatal.gravidity', 
            'prenatal.parity', 
            'prenatal.term', 
            'prenatal.preterm',
            'prenatal.abortion', 
            'prenatal.living', 
            'prenatal.syphilisResult', 
            'prenatal.penicillin', 
            'prenatal.hemoglobin',
            'prenatal.hematocrit', 
            'prenatal.urinalysis', 
            'prenatal.ttStatus', 
            'prenatal.tdDate',
        )
        ->get();

        return Inertia::render('Table/PreNatal', [
            'PRENATAL' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CheckUp/PreNatalCheckup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'required|string|max:255',
            'purok' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'age' => 'required|numeric',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:15',
            'modeOfTransaction' => 'required|string|max:255',
            'consultationDate' => 'required|date',
            'consultationTime' => 'required|date_format:H:i',
            'bloodPressure' => 'required|string|max:255',
            'temperature' => 'required|numeric|between:0,999.99',
            'height' => 'required|numeric|between:0,999.99',
            'weight' => 'required|numeric|between:0,999.99',
            'providerName' => 'required|string|max:255',
            'nameOfSpouse' => 'required|string|max:255',
            'emergencyContact' => 'required|string|max:255',
            'fourMember' => 'required|string|max:255',
            'philhealthStatus' => 'required|string|max:255',
            'philhealthId' => 'nullable|string|max:255',
            'menarche' => 'required|string|max:255',
            'sexualOnset' => 'required|string|max:255',
            'periodDuration' => 'required|string|max:255',
            'birthControl' => 'required|string|max:255',
            'intervalCycle' => 'required|string|max:255',
            'menopause' => 'required|string|max:255',
            'lmp' => 'required|date',
            'edc' => 'required|date',
            'gravidity' => 'required|string|max:255',
            'parity' => 'required|string|max:255',
            'term' => 'required|string|max:255',
            'preterm' => 'required|string|max:255',
            'abortion' => 'required|string|max:255',
            'living' => 'required|string|max:255',
            'syphilisResult' => 'required|string|max:255',
            'penicillin' => 'required|string|max:255',
            'hemoglobin' => 'required|numeric|between:0,999.99',
            'hematocrit' => 'required|numeric|between:0,999.99',
            'urinalysis' => 'required|string|max:255',
            'ttStatus' => 'required|string|max:255',
            'tdDate' => 'required|date',
        ]);

        // Split validated data for PersonalInformation
        $personalData = [
            'firstName' => $validatedData['firstName'],
            'lastName' => $validatedData['lastName'],
            'middleName' => $validatedData['middleName'],
            'purok' => $validatedData['purok'],
            'barangay' => $validatedData['barangay'],
            'age' => $validatedData['age'],
            'birthdate' => $validatedData['birthdate'],
            'contact' => $validatedData['contact'],
        ];

        // Save personal data to PersonalInformation table
        $personalInfo = PersonalInformation::create($personalData);

        // Prepare data for PreNatal table
        $prenatalData = [
            'personalId' => $personalInfo->personalId, // Link to personal information record
            'modeOfTransaction' => $validatedData['modeOfTransaction'],
            'consultationDate' => $validatedData['consultationDate'],
            'consultationTime' => $validatedData['consultationTime'],
            'bloodPressure' => $validatedData['bloodPressure'],
            'temperature' => $validatedData['temperature'],
            'height' => $validatedData['height'],
            'weight' => $validatedData['weight'],
            'providerName' => $validatedData['providerName'],
            'nameOfSpouse' => $validatedData['nameOfSpouse'],
            'emergencyContact' => $validatedData['emergencyContact'],
            'fourMember' => $validatedData['fourMember'],
            'philhealthStatus' => $validatedData['philhealthStatus'],
            'philhealthId' => $validatedData['philhealthId'],
            'menarche' => $validatedData['menarche'],
            'sexualOnset' => $validatedData['sexualOnset'],
            'periodDuration' => $validatedData['periodDuration'],
            'birthControl' => $validatedData['birthControl'],
            'intervalCycle' => $validatedData['intervalCycle'],
            'menopause' => $validatedData['menopause'],
            'lmp' => $validatedData['lmp'],
            'edc' => $validatedData['edc'],
            'gravidity' => $validatedData['gravidity'],
            'parity' => $validatedData['parity'],
            'term' => $validatedData['term'],
            'preterm' => $validatedData['preterm'],
            'abortion' => $validatedData['abortion'],
            'living' => $validatedData['living'],
            'syphilisResult' => $validatedData['syphilisResult'],
            'penicillin' => $validatedData['penicillin'],
            'hemoglobin' => $validatedData['hemoglobin'],
            'hematocrit' => $validatedData['hematocrit'],
            'urinalysis' => $validatedData['urinalysis'],
            'ttStatus' => $validatedData['ttStatus'],
            'tdDate' => $validatedData['tdDate'],
        ];

        // Save prenatal data to PreNatal table
        $prenatal = PreNatal::create($prenatalData);

        // Redirect back with a success message
        return back()->with('Success', 'Data saved successfully!');
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
