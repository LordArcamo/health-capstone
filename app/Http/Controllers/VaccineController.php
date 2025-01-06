<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use App\Models\VaccinationRecord;
use Inertia\Inertia;

class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function handle(Request $request)
    {
        // Get all patients for the dropdown
        $patients = PersonalInformation::select([
            'personalId',
            'firstName',
            'lastName',
            'middleName',
            'suffix',
            'purok',
            'barangay',
            'age',
            'birthdate',
            'contact',
            'sex'
        ])->get();

        // Get vaccinated patients with their history
        $data = PersonalInformation::with(['vaccinationRecords' => function($query) {
            $query->orderBy('dateOfVisit', 'desc');
        }])
        ->whereHas('vaccinationRecords')
        ->get()
        ->map(function($patient) {
            return [
                'personalId' => $patient->personalId,
                'firstName' => $patient->firstName,
                'lastName' => $patient->lastName,
                'middleName' => $patient->middleName,
                'suffix' => $patient->suffix,
                'purok' => $patient->purok,
                'barangay' => $patient->barangay,
                'age' => $patient->age,
                'birthdate' => $patient->birthdate,
                'contact' => $patient->contact,
                'sex' => $patient->sex,
                'vaccineCategory' => $patient->vaccinationRecords->first()->vaccineCategory,
                'vaccineType' => $patient->vaccinationRecords->first()->vaccineType,
                'nextAppointment' => $patient->vaccinationRecords->first()->nextAppointment,
                'history' => $patient->vaccinationRecords->map(function($record) {
                    return [
                        'id' => $record->vaccinationId,
                        'dateOfVisit' => $record->dateOfVisit,
                        'vaccineType' => $record->vaccineType,
                        'weight' => $record->weight,
                        'height' => $record->height,
                        'temperature' => $record->temperature,
                        'antigenGiven' => $record->antigenGiven,
                        'injectedBy' => $record->injectedBy,
                        'exclusivelyBreastfed' => $record->exclusivelyBreastfed,
                        'nextAppointment' => $record->nextAppointment,
                    ];
                })
            ];
        });

        return Inertia::render('Services/Vaccination', [
            'view' => 'index',
            'patients' => $patients,
            'VACCINATION' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalId = $request->get('patient_personalId');

        if ($personalId === 'new') {
            return Inertia::render('Services/Vaccination', [
                'personalInfo' => null, // No existing patient
            ]);
        }

        $personalInfo = $personalId ? PersonalInformation::find($personalId) : null;

        if (!$personalInfo) {
            return back()->withErrors(['error' => 'Patient not found']);
        }

        return Inertia::render('Services/Vaccination', [
            'personalInfo' => $personalInfo,
        ]);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('query');
        $patients = PersonalInformation::where('firstName', 'LIKE', "%{$searchTerm}%")
            ->orWhere('lastName', 'LIKE', "%{$searchTerm}%")
            ->orWhere('personalId', 'LIKE', "%{$searchTerm}%")
            ->with(['vaccinationRecords' => function($query) {
                $query->orderBy('dateOfVisit', 'desc');
            }])
            ->get()
            ->map(function($patient) {
                return [
                    'personalId' => $patient->personalId,
                    'firstName' => $patient->firstName,
                    'middleName' => $patient->middleName,
                    'lastName' => $patient->lastName,
                    'suffix' => $patient->suffix,
                    'age' => $patient->age,
                    'vaccineType' => $patient->vaccineType,
                    'barangay' => $patient->barangay,
                    'purok' => $patient->purok,
                    'vaccineCategory' => $patient->vaccineCategory,
                    'history' => $patient->vaccinationRecords->map(function($record) {
                        return [
                            'id' => $record->id,
                            'dateOfVisit' => $record->dateOfVisit,
                            'ageInMonths' => $record->ageInMonths,
                            'ageInYears' => $record->ageInYears,
                            'weight' => $record->weight,
                            'height' => $record->height,
                            'temperature' => $record->temperature,
                            'antigenGiven' => $record->antigenGiven,
                            'injectedBy' => $record->injectedBy,
                            'exclusivelyBreastfed' => $record->exclusivelyBreastfed,
                            'nextAppointment' => $record->nextAppointment,
                        ];
                    })
                ];
            });

        return response()->json($patients);
    }

    /**
     * Store a newly created vaccination record.
     */
    public function store(Request $request)
    {
        // Log the incoming request
        \Log::info('Incoming request data:', $request->all());

        // Validate request data
        $validatedData = $request->validate([
            'patient.personalId' => 'nullable|exists:personal_information,personalId',
            'id' => 'nullable|exists:users,id',
            'patient.isExisting' => 'required|boolean',
            'patient.firstName' => 'required_without:patient.personalId|string|max:100',
            'patient.lastName' => 'required_without:patient.personalId|string|max:100',
            'patient.middleName' => 'required_without:patient.personalId|string|max:100',
            'patient.suffix' => 'nullable|string|max:10',
            'patient.purok' => 'nullable|string|max:100',
            'patient.barangay' => 'nullable|string|max:100',
            'patient.age' => 'nullable|numeric',
            'patient.birthdate' => 'nullable|date',
            'patient.contact' => 'nullable|string|max:15',
            'patient.sex' => 'nullable|string|max:10',
            'vaccinationDetails.vaccineCategory' => 'required|string|max:255',
            'vaccinationDetails.vaccineType' => 'required|string|max:255',
            'vaccinationDetails.dateOfVisit' => 'required|date',
            'vaccinationDetails.age' => 'required|numeric',
            'vaccinationDetails.weight' => 'nullable|numeric|between:0,500',
            'vaccinationDetails.height' => 'nullable|numeric|between:0,300',
            'vaccinationDetails.temperature' => 'nullable|numeric|between:0,100',
            'vaccinationDetails.antigenGiven' => 'required|string|max:255',
            'vaccinationDetails.injectedBy' => 'required|string|max:255',
            'vaccinationDetails.exclusivelyBreastfed' => 'nullable|string|max:20',
            'vaccinationDetails.nextAppointment' => 'nullable|date',
        ]);

        $userId = auth()->id() ?? $validatedData['id'];

        if (!$userId) {
            return back()->withErrors(['error' => 'User ID is required.']);
        }

        $personalInfo = null;

        if ($validatedData['patient']['isExisting']) {
            // For existing patient, just get the reference without updating
            $personalInfo = PersonalInformation::findOrFail($validatedData['patient']['personalId']);
        } else {
            // Only create new patient if it's not an existing one
            $personalInfo = PersonalInformation::create([
                'firstName' => $validatedData['patient']['firstName'],
                'lastName' => $validatedData['patient']['lastName'],
                'middleName' => $validatedData['patient']['middleName'],
                'suffix' => $validatedData['patient']['suffix'],
                'purok' => $validatedData['patient']['purok'],
                'barangay' => $validatedData['patient']['barangay'],
                'age' => $validatedData['patient']['age'],
                'birthdate' => $validatedData['patient']['birthdate'],
                'contact' => $validatedData['patient']['contact'],
                'sex' => $validatedData['patient']['sex'],
            ]);
        }

        // Save the vaccination details
        VaccinationRecord::create([
            'personalId' => $personalInfo->personalId,
            'id' => $userId,
            'vaccineCategory' => $validatedData['vaccinationDetails']['vaccineCategory'],
            'vaccineType' => $validatedData['vaccinationDetails']['vaccineType'],
            'dateOfVisit' => $validatedData['vaccinationDetails']['dateOfVisit'],
            'ageInMonths' => $validatedData['vaccinationDetails']['vaccineCategory'] === 'Under 1 Year' ? $validatedData['vaccinationDetails']['age'] : null,
            'ageInYears' => $validatedData['vaccinationDetails']['vaccineCategory'] !== 'Under 1 Year' ? $validatedData['vaccinationDetails']['age'] : null,
            'weight' => $validatedData['vaccinationDetails']['weight'] ?? null,
            'height' => $validatedData['vaccinationDetails']['height'] ?? null,
            'temperature' => $validatedData['vaccinationDetails']['temperature'] ?? null,
            'antigenGiven' => $validatedData['vaccinationDetails']['antigenGiven'],
            'injectedBy' => $validatedData['vaccinationDetails']['injectedBy'],
            'exclusivelyBreastfed' => $validatedData['vaccinationDetails']['exclusivelyBreastfed'] ?? 'None',
            'nextAppointment' => $validatedData['vaccinationDetails']['nextAppointment'] ?? null,
        ]);

        // Log the action and return success response
        \Log::info('Vaccination record saved successfully:', [
            'personalId' => $personalInfo->personalId,
            'vaccinationDetails' => $validatedData['vaccinationDetails'],
        ]);
        return back()->with('success', 'Vaccination record saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $patients = PersonalInformation::all(); // Replace with your query to get patients data

        return Inertia::render('PatientTable', [
            'patients' => $patients,
        ]);
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

    /**
     * Get vaccination history for a specific patient
     */
    public function getHistory($personalId)
    {
        try {
            $history = VaccinationRecord::where('personalId', $personalId)
                ->orderBy('dateOfVisit', 'desc')
                ->get();

            return Inertia::render('Components/VaccinationModals/ViewHistoryModal', [
                'vaccinationHistory' => $history
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to fetch vaccination history']);
        }
    }
}
