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
        // Use Eloquent to join the PersonalInformation and itr tables
        $data = PersonalInformation::join('itr', 'personal_information.personalId', '=', 'itr.personalId')
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
            ->distinct() // Ensure no duplicate entries
            ->get();
    
        // Pass the joined data to the view
        return Inertia::render('Table/IndividualTreatmentRecord', [
            'ITR' => $data,
        ]);
    }
    



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalId = $request->get('patient_personalId');

        if ($personalId === 'new') {
            return Inertia::render('CheckUp/IndividualTreatmentRecordCheckup', [
                'personalInfo' => null, // No existing patient
            ]);
        }

        $personalInfo = $personalId ? PersonalInformation::find($personalId) : null;

        if (!$personalInfo) {
            return back()->withErrors(['error' => 'Patient not found']);
        }

        return Inertia::render('CheckUp/IndividualTreatmentRecordCheckup', [
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
            'personalId' => 'nullable|exists:personal_information,personalId',
            'firstName' => 'required_without:personalId|string|max:100',
            'lastName' => 'required_without:personalId|string|max:100',
            'middleName' => 'required_without:personalId|string|max:100',
            'suffix' => 'nullable|string|max:10',
            'purok' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'age' => 'nullable|numeric',
            'birthdate' => 'nullable|date',
            'contact' => 'nullable|string|max:15',
            'sex' => 'nullable|string|max:10',
            'consultationDate' => 'required|date',
            'consultationTime' => 'required|date_format:H:i',
            'modeOfTransaction' => 'required|string|max:50',
            'bloodPressure' => 'nullable|string|max:20',
            'temperature' => 'nullable|numeric|between:0,100',
            'height' => 'nullable|numeric|between:0,300',
            'weight' => 'nullable|numeric|between:0,500',
            'providerName' => 'required|string|max:100',
            'natureOfVisit' => 'required|string|max:100',
            'visitType' => 'required|string|max:50',
            'chiefComplaints' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'medication' => 'required|string|max:255',
        ]);

        $personalInfo = null;

        if (isset($validatedData['personalId'])) {
            // Patient exists, update personal information
            $personalInfo = PersonalInformation::find($validatedData['personalId']);
            if ($personalInfo) {
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
            // Patient does not exist, create new record
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

        // Save the ITR (Individual Treatment Record) data
        CheckUp::create([
            'personalId' => $personalInfo->personalId,
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
        ]);

        // Log the action and return success response
        \Log::info('Patient and ITR saved successfully:', $personalInfo->toArray());
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
