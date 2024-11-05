<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CheckUp;
use App\Models\PersonalInformation;

class CheckUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Use Eloquent to join the two tables
    $data = PersonalInformation::join('itr', 'personal_information.personalId', '=', 'itr.personalId')
        ->select(
            'personal_information.*', 
            'itr.consultationDate',
            'itr.consultationTime',
            'itr.modeOfTransaction',
            'itr.bloodPressure',
            'itr.temperature',
            'itr.height',
            'itr.weight',
            'itr.providerName',
            'itr.natureOfVisit',
            'itr.visitType',
            'itr.chiefComplaints',
            'itr.diagnosis',
            'itr.medication'
        )
        ->get();

    // Pass the joined data to the view
    return Inertia::render('Table/IndividualTreatmentRecord', [
        'ITR' => $data,
    ]);
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CheckUp/IndividualTreatmentRecordCheckup');
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
            'consultationDate' => 'required|date',
            'consultationTime' => 'required|date_format:H:i',
            'modeOfTransaction' => 'required|string|max:50',
            'bloodPressure' => 'required|string|max:20', 
            'temperature' => 'required|numeric|between:0,100', 
            'height' => 'required|numeric|between:0,300',
            'weight' => 'required|numeric|between:0,500', 
            'providerName' => 'required|string|max:100',
            'natureOfVisit' => 'required|string|max:100',
            'visitType' => 'required|string|max:50',
            'chiefComplaints' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'medication' => 'required|string|max:255',
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

        // Prepare data for CheckUp table
        $checkUpData = [
            'personalId' => $personalInfo->personalId, // Link to personal information record
            'consultationDate' => $validatedData['consultationDate'],
            'consultationTime' => $validatedData['consultationTime'],
            'modeOfTransaction' => $validatedData['modeOfTransaction'],
            'bloodPressure' => $validatedData['bloodPressure'],
            'temperature' => $validatedData['temperature'],
            'height' => $validatedData['height'],
            'weight' => $validatedData['weight'],
            'providerName' => $validatedData['providerName'],
            'natureOfVisit' => $validatedData['natureOfVisit'],
            'visitType' => $validatedData['visitType'],
            'chiefComplaints' => $validatedData['chiefComplaints'],
            'diagnosis' => $validatedData['diagnosis'],
            'medication' => $validatedData['medication'],
        ];

        // Save check-up data to CheckUp table
        $checkUp = CheckUp::create($checkUpData);

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
