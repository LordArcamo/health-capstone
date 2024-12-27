<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use App\Models\RiskManagement;
use Inertia\Inertia;

class RiskManagementController extends Controller
{

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

        // Get patients with their risk management records
        $data = PersonalInformation::join('risk_management', 'personal_information.personalId', '=', 'risk_management.personalId')
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
                'risk_management.foodIntake',
                'risk_management.physicalActivity',
                'risk_management.bloodGlucose',
                'risk_management.fbsRbs',
                'risk_management.bloodGlucoseDate',
                'risk_management.bloodLipids',
                'risk_management.totalCholesterol',
                'risk_management.bloodLipidsDate',
                'risk_management.urineKetones',
                'risk_management.urineKetoneLevel',
                'risk_management.urineKetonesDate',
                'risk_management.urineProtein',
                'risk_management.urineProteinLevel',
                'risk_management.urineProteinDate'
            )
            ->distinct() // Ensure no duplicate entries
            ->get();

        // Return the view with the patients and risk management data
        return Inertia::render('Services/RiskManagement', [
            'view' => 'index',
            'patients' => $patients,
            'RISK_MANAGEMENT' => $data,
        ]);
    }


    // public function index()
    // {
    //     $patients = PersonalInformation::all(); // Retrieve all patients
    //     return Inertia::render('Services/RiskManagement', [
    //         'patients' => $patients,
    //     ]);
    // }

    public function store(Request $request)
    {
        // Log incoming request data for debugging
        \Log::info('Incoming request data:', $request->all());

        // Validate incoming data
        $validatedData = $request->validate([
            'patient.personalId' => 'nullable|exists:personal_information,personalId',
            'patient.isExisting' => 'required|boolean',
            'patient.firstName' => 'required_without:patient.personalId|string|max:100',
            'patient.lastName' => 'required_without:patient.personalId|string|max:100',
            'patient.middleName' => 'nullable|string|max:100',
            'patient.suffix' => 'nullable|string|max:10',
            'patient.purok' => 'nullable|string|max:100',
            'patient.barangay' => 'nullable|string|max:100',
            'patient.age' => 'nullable|numeric',
            'patient.birthdate' => 'nullable|date',
            'patient.contact' => 'nullable|string|max:15',
            'patient.sex' => 'nullable|string|max:10',
            'riskDetails.foodIntake' => 'nullable|in:Yes,No',
            'riskDetails.physicalActivity' => 'nullable|in:Yes,No',
            'riskDetails.bloodGlucose' => 'nullable|in:Yes,No',
            'riskDetails.fbsRbs' => 'nullable|numeric|between:0,500',
            'riskDetails.bloodGlucoseDate' => 'nullable|date',
            'riskDetails.bloodLipids' => 'nullable|in:Yes,No',
            'riskDetails.totalCholesterol' => 'nullable|numeric|between:0,500',
            'riskDetails.bloodLipidsDate' => 'nullable|date',
            'riskDetails.urineKetones' => 'nullable|in:Yes,No',
            'riskDetails.urineKetoneLevel' => 'nullable|numeric|between:0,500',
            'riskDetails.urineKetonesDate' => 'nullable|date',
            'riskDetails.urineProtein' => 'nullable|in:Yes,No',
            'riskDetails.urineProteinLevel' => 'nullable|numeric|between:0,500',
            'riskDetails.urineProteinDate' => 'nullable|date',
        ]);

        $personalInfo = null;

        // Handle patient data
        if ($validatedData['patient']['isExisting']) {
            // Fetch existing patient
            $personalInfo = PersonalInformation::findOrFail($validatedData['patient']['personalId']);
        } else {
            // Create a new patient if it's not an existing one
            $personalInfo = PersonalInformation::create([
                'firstName' => $validatedData['patient']['firstName'],
                'lastName' => $validatedData['patient']['lastName'],
                'middleName' => $validatedData['patient']['middleName'] ?? null,
                'suffix' => $validatedData['patient']['suffix'] ?? null,
                'purok' => $validatedData['patient']['purok'] ?? null,
                'barangay' => $validatedData['patient']['barangay'] ?? null,
                'age' => $validatedData['patient']['age'] ?? null,
                'birthdate' => $validatedData['patient']['birthdate'] ?? null,
                'contact' => $validatedData['patient']['contact'] ?? null,
                'sex' => $validatedData['patient']['sex'] ?? null,
            ]);
        }

        // Save risk management details
        $riskManagement = RiskManagement::create([
            'personalId' => $personalInfo->personalId,
            'foodIntake' => $validatedData['riskDetails']['foodIntake'] ?? null,
            'physicalActivity' => $validatedData['riskDetails']['physicalActivity'] ?? null,
            'bloodGlucose' => $validatedData['riskDetails']['bloodGlucose'] ?? null,
            'fbsRbs' => $validatedData['riskDetails']['fbsRbs'] ?? null,
            'bloodGlucoseDate' => $validatedData['riskDetails']['bloodGlucoseDate'] ?? null,
            'bloodLipids' => $validatedData['riskDetails']['bloodLipids'] ?? null,
            'totalCholesterol' => $validatedData['riskDetails']['totalCholesterol'] ?? null,
            'bloodLipidsDate' => $validatedData['riskDetails']['bloodLipidsDate'] ?? null,
            'urineKetones' => $validatedData['riskDetails']['urineKetones'] ?? null,
            'urineKetoneLevel' => $validatedData['riskDetails']['urineKetoneLevel'] ?? null,
            'urineKetonesDate' => $validatedData['riskDetails']['urineKetonesDate'] ?? null,
            'urineProtein' => $validatedData['riskDetails']['urineProtein'] ?? null,
            'urineProteinLevel' => $validatedData['riskDetails']['urineProteinLevel'] ?? null,
            'urineProteinDate' => $validatedData['riskDetails']['urineProteinDate'] ?? null,
        ]);

        // Log success and return response
        \Log::info('Risk Management data saved successfully:', [
            'personalId' => $personalInfo->personalId,
            'riskDetails' => $validatedData['riskDetails'],
        ]);

        return back()->with('success', 'Risk Management data saved successfully!');
    }

    
    public function search(Request $request)
    {
        $query = $request->get('query');
        \Log::debug("Search query: " . $query); // Log query to check if it's coming through

        // Trim and lower case the query for more flexible matching
        $query = trim($query);
        $queryLower = strtolower($query);

        $patients = PersonalInformation::where(function($q) use ($queryLower) {
            $q->whereRaw("LOWER(firstName) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(lastName) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(personalId) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(CONCAT(firstName, ' ', lastName)) LIKE ?", ["%$queryLower%"]); // Search by full name
        })->get();

        return Inertia::render('Services/RiskManagement', [
            'patients' => $patients ?? [],
        ]);
    }

    public function show()
    {
        $patients = PersonalInformation::all(); // Replace with your query to get patients data

        return Inertia::render('PatientTable', [
            'patients' => $patients,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Logic to update an existing risk assessment
        // Validate and update the data
        return redirect()->route('risk-management.index');
    }

    public function destroy($id)
    {
        // Logic to delete a risk assessment
        return redirect()->route('risk-management.index');
    }
}
