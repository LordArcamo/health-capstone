<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CheckUp;
use App\Models\PersonalInformation;
use App\Models\ConsultationDetails;
use App\Models\User;
use App\Models\VisitInformation;

class CheckUpController extends Controller
{
    public function import(Request $request)
    {
        set_time_limit(300); // Increase time limit to 5 minutes

        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data); // Extract the header row

        DB::transaction(function () use ($data, $header) {
            foreach ($data as $row) {
                $patientData = array_combine($header, $row);

                // Convert consultationTime to MySQL TIME format
                $patientData['consultationTime'] = date('H:i:s', strtotime($patientData['consultationTime']));

                // Convert numeric fields to proper types
                $patientData['temperature'] = (float) $patientData['temperature'];
                $patientData['height'] = (float) $patientData['height'];
                $patientData['weight'] = (float) $patientData['weight'];
                $patientData['age'] = (int) $patientData['age'];

                // Validate the row
                $validator = Validator::make($patientData, [
                    'firstName' => 'required|string|max:100',
                    'lastName' => 'required|string|max:100',
                    'middleName' => 'nullable|string|max:100',
                    'suffix' => 'nullable|string|max:10',
                    'purok' => 'nullable|string|max:100',
                    'barangay' => 'nullable|string|max:100',
                    'age' => 'nullable|integer|min:0|max:150',
                    'birthdate' => 'nullable|date',
                    'contact' => 'nullable|string|max:15',
                    'sex' => 'required|string|in:Male,Female',
                    'consultationDate' => 'required|date',
                    'consultationTime' => 'required|date_format:H:i:s',
                    'modeOfTransaction' => 'required|string|max:50',
                    'bloodPressure' => 'nullable|string|max:20',
                    'temperature' => 'nullable|numeric|between:35,42',
                    'height' => 'nullable|numeric|between:0,300',
                    'weight' => 'nullable|numeric|between:0,500',
                    'providerName' => 'required|string|max:100',
                    'natureOfVisit' => 'required|string|max:100',
                    'visitType' => 'required|string|max:50',
                    'referredFrom' => 'nullable|string|max:100',
                    'referredTo' => 'nullable|string|max:100',
                    'reasonsForReferral' => 'nullable|string|max:255',
                    'referredBy' => 'nullable|string|max:100',
                    'chiefComplaints' => 'required|string',
                    'diagnosis' => 'required|string',
                    'medication' => 'required|string',
                    'requireLabTest' => 'nullable|string',
                    'status' => 'nullable|string',

                ]);

                if ($validator->fails()) {
                    \Log::error('Validation failed for row: ' . json_encode($patientData));
                    \Log::error('Validation errors: ' . json_encode($validator->errors()->all()));
                    throw new \Exception('Validation failed for row: ' . json_encode($patientData));
                }

                try {
                    // Create PersonalInformation record
                    $personalInfo = PersonalInformation::create([
                        'firstName' => $patientData['firstName'],
                        'lastName' => $patientData['lastName'],
                        'middleName' => $patientData['middleName'],
                        'suffix' => $patientData['suffix'] ?? null,
                        'purok' => $patientData['purok'],
                        'barangay' => $patientData['barangay'],
                        'age' => $patientData['age'],
                        'birthdate' => $patientData['birthdate'],
                        'contact' => $patientData['contact'],
                        'sex' => $patientData['sex'],
                    ]);

                    // Create ConsultationDetails record
                    $consultationDetails = ConsultationDetails::create([
                        'personalId' => $personalInfo->personalId,
                        'id' => auth()->id(), // Add authenticated user ID
                        'consultationDate' => $patientData['consultationDate'],
                        'consultationTime' => $patientData['consultationTime'],
                        'modeOfTransaction' => $patientData['modeOfTransaction'],
                        'bloodPressure' => $patientData['bloodPressure'],
                        'temperature' => $patientData['temperature'],
                        'height' => $patientData['height'],
                        'weight' => $patientData['weight'],
                        'providerName' => $patientData['providerName'],
                        'natureOfVisit' => $patientData['natureOfVisit'],
                        'visitType' => $patientData['visitType'],
                        'referredFrom' => $patientData['referredFrom'] ?? 'None',
                        'referredTo' => $patientData['referredTo'] ?? 'None',
                        'reasonsForReferral' => $patientData['reasonsForReferral'] ?? 'None',
                        'referredBy' => $patientData['referredBy'] ?? 'None',
                        'status' => $patientData['status'] ?? 'None',
                    ]);

                    // Create VisitInformation record
                    VisitInformation::create([
                        'consultationDetailsID' => $consultationDetails->consultationDetailsID,
                        'id' => auth()->id(), // Add authenticated user ID
                        'chiefComplaints' => $patientData['chiefComplaints'],
                        'diagnosis' => $patientData['diagnosis'],
                        'medication' => $patientData['medication'],
                        'requireLabTest' => $patientData['requireLabTest'] ?? null,
                        'selectedLabTests' => $patientData['selectedLabTests'] ?? null,
                    ]);

                } catch (\Exception $e) {
                    \Log::error('Error creating records: ' . $e->getMessage());
                    throw $e;
                }
            }
        });

        return redirect()->back()->with('success', 'Patients imported successfully!');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PersonalInformation::join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
        ->leftJoin('visit_information', 'consultation_details.consultationDetailsID', '=', 'visit_information.consultationDetailsID')
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
            'consultation_details.providerName',
            'consultation_details.natureOfVisit',
            'consultation_details.visitType',

            // Updated Status Logic: Pending, Completed, or Cancelled
            DB::raw("
                CASE
                    WHEN consultation_details.status = 'cancelled' THEN 'Cancelled'
                    WHEN visit_information.consultationDetailsID IS NULL THEN 'Pending'
                    ELSE 'Completed'
                END AS status
            "),

            'visit_information.chiefComplaints',
            'visit_information.diagnosis',
            'visit_information.medication'
        )
        ->distinct()
        ->get();

    return Inertia::render('Table/IndividualTreatmentRecord', [
        'ITR' => $data,
    ]);

    // Query the CheckUp model to get the data needed for the chart
    $checkups = ConsultationDetails::selectRaw('
            MONTH(consultationDate) as month,
            YEAR(consultationDate) as year,
            sex,
            count(*) as case_count
        ')
        ->groupByRaw('MONTH(consultationDate), YEAR(consultationDate), sex')
        ->get();

    // Organize data for chart
    $chartData = [
        'months' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        'years' => $checkups->pluck('year')->unique()->sort(),
        'data' => $checkups->groupBy('year')->map(function ($yearData) {
            return $yearData->groupBy('month')->map(function ($monthData) {
                return [
                    'male' => $monthData->where('sex', 'Male')->sum('case_count'),
                    'female' => $monthData->where('sex', 'Female')->sum('case_count'),
                ];
            });
        }),
    ];


    return Inertia::render('ChartPage', [
        'chartData' => $chartData,
    ]);
    }



    public function testPatient()
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
     return Inertia::render('Table/Patient', [
         'ITR' => $data,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalId = $request->get('patient_personalId');
        $natureOfVisit = $request->get('natureOfVisit');

        // Log the request parameters
        \Log::info('ITR Create Request:', [
            'personalId' => $personalId,
            'natureOfVisit' => $natureOfVisit
        ]);

        if ($personalId === 'new') {
            return Inertia::render('CheckUp/IndividualTreatmentRecordCheckup', [
                'personalInfo' => null, // No existing patient
                'natureOfVisit' => $natureOfVisit,
            ]);
        }

        $personalInfo = $personalId ? PersonalInformation::find($personalId) : null;

        if (!$personalInfo) {
            return back()->withErrors(['error' => 'Patient not found']);
        }

        return Inertia::render('CheckUp/IndividualTreatmentRecordCheckup', [
            'personalInfo' => $personalInfo,
            'natureOfVisit' => $natureOfVisit,
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
            'referredFrom' => 'nullable|string|max:255',
            'referredTo' => 'nullable|string|max:255',
            'reasonsForReferral' => 'nullable|string|max:255',
            'referredBy' => 'nullable|string|max:255',
            'providerName' => 'required|string|max:100',
            'natureOfVisit' => 'required|string|max:100',
            'visitType' => 'required|string|max:50',
            'chiefComplaints' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'medication' => 'required|string|max:255',
        ]);

        $personalInfo = null;

        if (isset($validatedData['personalId'])) {
            // Update existing patient
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
                return back()->withErrors(['error' => 'Patient not found']);
            }
        } else {
            // Create new patient
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
            'referredFrom' => $validatedData['referredFrom'] ?? 'None',
            'referredTo' => $validatedData['referredTo'] ?? 'None',
            'reasonsForReferral' => $validatedData['reasonsForReferral'] ?? 'None',
            'referredBy' => $validatedData['referredBy'] ?? 'None',
            'providerName' => $validatedData['providerName'],
            'natureOfVisit' => $validatedData['natureOfVisit'],
            'visitType' => $validatedData['visitType'],
            'chiefComplaints' => $validatedData['chiefComplaints'],
            'diagnosis' => $validatedData['diagnosis'],
            'medication' => $validatedData['medication'],
        ]);

        // Log success
        \Log::info('Patient and ITR saved successfully.');

     // Redirect to the thank-you page with the checkUpType
     return Inertia::location('/checkup/thank-you/itr');
    }

    public function storeDetails(Request $request)
    {
        // Log the incoming request
        \Log::info('Incoming request data:', $request->all());

        // Validate request data
        $validatedData = $request->validate([
            'personalId' => 'nullable|exists:personal_information,personalId',
            'id' => 'nullable|exists:users,id', // Validate against the users table
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
            'referredFrom' => 'nullable|string|max:255',
            'referredTo' => 'nullable|string|max:255',
            'reasonsForReferral' => 'nullable|string|max:255',
            'referredBy' => 'nullable|string|max:255',
            'natureOfVisit' => 'required|string|max:100',
            'visitType' => 'required|string|max:50',
            'providerName' => 'required|string|max:100',
        ]);

        // Use the authenticated user's ID or validate the `id` field from the request
        $userId = auth()->id() ?? $validatedData['id'];

        if (!$userId) {
            return back()->withErrors(['error' => 'User ID is required.']);
        }

        $personalInfo = null;

        if (isset($validatedData['personalId'])) {
            // Update existing patient
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
            // Create new patient
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
        ConsultationDetails::create([
            'personalId' => $personalInfo->personalId,
            'id' => $userId, // Save the user ID as a foreign key
            'consultationDate' => $validatedData['consultationDate'],
            'consultationTime' => $validatedData['consultationTime'],
            'modeOfTransaction' => $validatedData['modeOfTransaction'],
            'bloodPressure' => $validatedData['bloodPressure'],
            'temperature' => $validatedData['temperature'],
            'height' => $validatedData['height'],
            'weight' => $validatedData['weight'],
            'referredFrom' => $validatedData['referredFrom'] ?? 'None',
            'referredTo' => $validatedData['referredTo'] ?? 'None',
            'reasonsForReferral' => $validatedData['reasonsForReferral'] ?? 'None',
            'referredBy' => $validatedData['referredBy'] ?? 'None',
            'natureOfVisit' => $validatedData['natureOfVisit'],
            'visitType' => $validatedData['visitType'],
            'providerName' => $validatedData['providerName'],
        ]);

        // Log success
        \Log::info('Patient and ITR saved successfully.');

        // Redirect to the thank-you page
        return Inertia::location('/checkup/thank-you/itr');
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
