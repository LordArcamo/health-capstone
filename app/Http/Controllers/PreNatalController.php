<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PreNatal;
use App\Models\PersonalInformation;
use App\Models\GeneralTrimester;
use App\Models\PrenatalConsultationDetails;
use App\Models\PrenatalVisitInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PreNatalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PersonalInformation::join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
        ->leftJoin('prenatal_visit_information', 'prenatal_consultation_details.prenatalConsultationDetailsID', '=', 'prenatal_visit_information.prenatalConsultationDetailsID')
        ->select(
            'personal_information.personalId',
            'personal_information.firstName',
            'personal_information.lastName',
            'personal_information.middleName',
            'personal_information.purok',
            'personal_information.barangay',
            'personal_information.age',
            'personal_information.birthdate',
            'personal_information.contact',
            'prenatal_consultation_details.prenatalConsultationDetailsID',
            'prenatal_consultation_details.modeOfTransaction',
            'prenatal_consultation_details.consultationDate',
            'prenatal_consultation_details.consultationTime',
            'prenatal_consultation_details.bloodPressure',
            'prenatal_consultation_details.temperature',
            'prenatal_consultation_details.height',
            'prenatal_consultation_details.weight',
            'prenatal_consultation_details.providerName',
            'prenatal_consultation_details.nameOfSpouse',
            'prenatal_consultation_details.emergencyContact',
            'prenatal_consultation_details.fourMember',
            'prenatal_consultation_details.philhealthStatus',
            'prenatal_consultation_details.philhealthNo',

            // Updated Status Logic: Pending, Completed, or Cancelled
            DB::raw("
                CASE
                    WHEN prenatal_consultation_details.status = 'Cancelled' THEN 'Cancelled'
                    WHEN prenatal_visit_information.prenatalConsultationDetailsID IS NULL THEN 'In Queue'
                    ELSE 'Completed'
                END AS status
            "),

            'prenatal_visit_information.menarche',
            'prenatal_visit_information.sexualOnset',
            'prenatal_visit_information.periodDuration',
            'prenatal_visit_information.birthControl',
            'prenatal_visit_information.intervalCycle',
            'prenatal_visit_information.menopause',
            'prenatal_visit_information.lmp',
            'prenatal_visit_information.edc',
            'prenatal_visit_information.gravidity',
            'prenatal_visit_information.parity',
            'prenatal_visit_information.term',
            'prenatal_visit_information.preterm',
            'prenatal_visit_information.abortion',
            'prenatal_visit_information.living',
            'prenatal_visit_information.syphilisResult',
            'prenatal_visit_information.penicillin',
            'prenatal_visit_information.hemoglobin',
            'prenatal_visit_information.hematocrit',
            'prenatal_visit_information.urinalysis',
            'prenatal_visit_information.ttStatus',
            'prenatal_visit_information.tdDate'
        )
        ->distinct()
        ->get();

    return Inertia::render('Table/PreNatal', [
        'PRENATAL' => $data,
    ]);

    }

    public function fetchTrimesterData($prenatalConsultationDetailsID, $trimester)
    {
        try {
            // Fetch the latest data for this prenatalConsultationDetailsID and trimester
            $trimesterData = GeneralTrimester::where('prenatalConsultationDetailsID', $prenatalConsultationDetailsID)
                ->where('trimester', $trimester)
                ->latest('created_at')
                ->first();

            if ($trimesterData) {
                // Load the appropriate checkbox relationship based on trimester
                $checkboxData = null;
                switch($trimester) {
                    case '1':
                        $checkboxData = $trimesterData->checkbox1()->first();
                        break;
                    case '2':
                        $checkboxData = $trimesterData->checkbox2()->first();
                        break;
                    case '3':
                    case '4':
                    case '5':
                        $checkboxData = $trimesterData->checkbox3()->first();
                        break;
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'generalTrimester' => $trimesterData,
                        'checkbox' . $trimester => $checkboxData
                    ],
                    'message' => 'Latest data found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'No existing data'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in fetchTrimesterData: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching trimester data: ' . $e->getMessage()
            ], 500);
        }
    }


    public function import(Request $request)
    {
        set_time_limit(300); // Increase time limit to 5 minutes

        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data); // Extract the header row

        DB::transaction(function () use ($data, $header) {
            foreach ($data as $row) {
                $prenatalData = array_combine($header, $row);

                // Convert consultationTime to MySQL TIME format
                $prenatalData['consultationTime'] = date('H:i:s', strtotime($prenatalData['consultationTime']));

                // Convert numeric fields to proper types
                $prenatalData['temperature'] = (float) $prenatalData['temperature'];
                $prenatalData['height'] = (float) $prenatalData['height'];
                $prenatalData['weight'] = (float) $prenatalData['weight'];
                $prenatalData['age'] = (int) $prenatalData['age'];

                // Validate the row
                $validator = Validator::make($prenatalData, [
                    'firstName' => 'required|string|max:100',
                    'lastName' => 'required|string|max:100',
                    'middleName' => 'nullable|string|max:100',
                    'purok' => 'nullable|string|max:100',
                    'barangay' => 'nullable|string|max:100',
                    'age' => 'required|integer|min:0|max:150',
                    'birthdate' => 'required|date',
                    'contact' => 'nullable|string|max:15',
                    'sex' => 'required|string|in:Female',
                    'modeOfTransaction' => 'required|string|max:50',
                    'consultationDate' => 'required|date',
                    'consultationTime' => 'required|date_format:H:i:s',
                    'bloodPressure' => 'nullable|string|max:20',
                    'temperature' => 'nullable|numeric|between:35,42',
                    'height' => 'nullable|numeric|between:0,300',
                    'weight' => 'nullable|numeric|between:0,500',
                    'providerName' => 'required|string|max:100',
                    'nameOfSpouse' => 'required|string|max:100',
                    'emergencyContact' => 'required|string|max:15',
                    'fourMember' => 'required|string|max:100',
                    'philhealthStatus' => 'required|string|max:50',
                    'philhealthNo' => 'nullable|string|max:50',
                    'menarche' => 'required|string|max:50',
                    'sexualOnset' => 'required|string|max:50',
                    'periodDuration' => 'required|string|max:50',
                    'birthControl' => 'required|string|max:100',
                    'intervalCycle' => 'required|string|max:50',
                    'menopause' => 'nullable|string|max:50',
                    'lmp' => 'required|date',
                    'edc' => 'required|date',
                    'gravidity' => 'required|integer|min:0',
                    'parity' => 'required|integer|min:0',
                    'term' => 'required|integer|min:0',
                    'preterm' => 'required|integer|min:0',
                    'abortion' => 'required|integer|min:0',
                    'living' => 'required|integer|min:0',
                ]);

                if ($validator->fails()) {
                    \Log::error('Validation failed for row: ' . json_encode($prenatalData));
                    \Log::error('Validation errors: ' . json_encode($validator->errors()->all()));
                    throw new \Exception('Validation failed for row: ' . json_encode($prenatalData));
                }

                try {
                    // Insert data in PersonalInformation
                    $personalInfo = PersonalInformation::create([
                        'firstName' => $prenatalData['firstName'],
                        'lastName' => $prenatalData['lastName'],
                        'middleName' => $prenatalData['middleName'],
                        'purok' => $prenatalData['purok'],
                        'barangay' => $prenatalData['barangay'],
                        'age' => $prenatalData['age'],
                        'birthdate' => $prenatalData['birthdate'],
                        'contact' => $prenatalData['contact'],
                        'sex' => $prenatalData['sex'],
                    ]);

                    // Insert data in PrenatalConsultationDetails
                    $consultationDetails = PrenatalConsultationDetails::create([
                        'personalId' => $personalInfo->personalId,
                        'id' => auth()->id(), // Add authenticated user ID
                        'modeOfTransaction' => $prenatalData['modeOfTransaction'],
                        'consultationDate' => $prenatalData['consultationDate'],
                        'consultationTime' => $prenatalData['consultationTime'],
                        'bloodPressure' => $prenatalData['bloodPressure'],
                        'temperature' => $prenatalData['temperature'],
                        'height' => $prenatalData['height'],
                        'weight' => $prenatalData['weight'],
                        'providerName' => $prenatalData['providerName'],
                        'nameOfSpouse' => $prenatalData['nameOfSpouse'],
                        'emergencyContact' => $prenatalData['emergencyContact'],
                        'fourMember' => $prenatalData['fourMember'],
                        'philhealthStatus' => $prenatalData['philhealthStatus'],
                        'philhealthNo' => $prenatalData['philhealthNo'],
                    ]);

                    // Insert data in PrenatalVisitInformation
                    PrenatalVisitInformation::create([
                        'prenatalConsultationDetailsID' => $consultationDetails->prenatalConsultationDetailsID,
                        'id' => auth()->id(), // Add authenticated user ID
                        'menarche' => $prenatalData['menarche'],
                        'sexualOnset' => $prenatalData['sexualOnset'],
                        'periodDuration' => $prenatalData['periodDuration'],
                        'birthControl' => $prenatalData['birthControl'],
                        'intervalCycle' => $prenatalData['intervalCycle'],
                        'menopause' => $prenatalData['menopause'],
                        'lmp' => $prenatalData['lmp'],
                        'edc' => $prenatalData['edc'],
                        'gravidity' => (int)$prenatalData['gravidity'],
                        'parity' => (int)$prenatalData['parity'],
                        'term' => (int)$prenatalData['term'],
                        'preterm' => (int)$prenatalData['preterm'],
                        'abortion' => (int)$prenatalData['abortion'],
                        'living' => (int)$prenatalData['living'],
                    ]);

                } catch (\Exception $e) {
                    \Log::error('Error creating records: ' . $e->getMessage());
                    throw $e;
                }
            }
        });

        return redirect()->back()->with('success', 'Prenatal records imported successfully!');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $personalId = $request->get('patient_personalId');

        if ($personalId === 'new') {
            return Inertia::render('CheckUp/PreNatalCheckup', [
                'personalInfo' => null, // No existing patient
            ]);
        }

        $personalInfo = $personalId ? PersonalInformation::find($personalId) : null;

        if (!$personalInfo) {
            return back()->withErrors(['error' => 'Patient not found']);
        }

        return Inertia::render('CheckUp/PreNatalCheckup', [
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
            'firstName' => 'required_without:personalId|string|max:255',
            'lastName' => 'required_without:personalId|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'purok' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'age' => 'required|numeric',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:15',
            'sex' => 'required|string|max:15',

            // PreNatal Data
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
            'philhealthNo' => 'nullable|string|max:255',
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

        try {
            $personalInfo = null;

            // Handle existing or new personal information
            if (!empty($validatedData['personalId'])) {
                // Update existing personal information
                $personalInfo = PersonalInformation::findOrFail($validatedData['personalId']);
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
                // Create new personal information
                $personalInfo = PersonalInformation::create([
                    'firstName' => $validatedData['firstName'],
                    'lastName' => $validatedData['lastName'],
                    'middleName' => $validatedData['middleName'],
                    'suffix' => 'None',
                    'purok' => $validatedData['purok'],
                    'barangay' => $validatedData['barangay'],
                    'age' => $validatedData['age'],
                    'birthdate' => $validatedData['birthdate'],
                    'contact' => $validatedData['contact'],
                    'sex' => 'Female',
                ]);
            }

            // Save PreNatal data
            PreNatal::create([
                'personalId' => $personalInfo->personalId,
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
                'philhealthNo' => $validatedData['philhealthNo'] ?? null,
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
            ]);

            // Log the success
            \Log::info('Data saved successfully for personalId:', [
                'personalId' => $personalInfo->personalId,
            ]);

            // Redirect with success
            return Inertia::location('/checkup/thank-you/prenatal');
        } catch (\Exception $e) {
            \Log::error('Error saving data:', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'An unexpected error occurred. Please try again later.']);
        }
    }

    public function storeDetails(Request $request)
    {
        // Log the incoming request
        \Log::info('Incoming request data:', $request->all());

        // Validate request data
        $validatedData = $request->validate([
            // Personal Information
            'personalId' => 'nullable|exists:personal_information,personalId',
            'id' => 'nullable|exists:users,id',
            'firstName' => 'required_without:personalId|string|max:255',
            'lastName' => 'required_without:personalId|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'purok' => 'nullable|string|max:100',
            'barangay' => 'nullable|string|max:100',
            'age' => 'required|numeric',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:15',
            'sex' => 'required|string|max:15',

            // PreNatal Data
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
            'philhealthNo' => 'nullable|string|max:255',
        ]);

        $userId = auth()->id() ?? $validatedData['id'];

        if (!$userId) {
            return back()->withErrors(['error' => 'User ID is required.']);
        }

        try {
            $personalInfo = null;

            // Handle existing or new personal information
            if (!empty($validatedData['personalId'])) {
                // Update existing personal information
                $personalInfo = PersonalInformation::findOrFail($validatedData['personalId']);
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
                // Create new personal information
                $personalInfo = PersonalInformation::create([
                    'firstName' => $validatedData['firstName'],
                    'lastName' => $validatedData['lastName'],
                    'middleName' => $validatedData['middleName'],
                    'suffix' => 'None',
                    'purok' => $validatedData['purok'],
                    'barangay' => $validatedData['barangay'],
                    'age' => $validatedData['age'],
                    'birthdate' => $validatedData['birthdate'],
                    'contact' => $validatedData['contact'],
                    'sex' => 'Female',
                ]);
            }

            // Save PreNatal data
            PrenatalConsultationDetails::create([
                'personalId' => $personalInfo->personalId,
                'id' => $userId,
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
                'philhealthNo' => $validatedData['philhealthNo'] ?? null,
            ]);

            // Log the success
            \Log::info('Data saved successfully for personalId:', [
                'personalId' => $personalInfo->personalId,
            ]);

            // Redirect with success
            return Inertia::location('/checkup/thank-you/prenatal');
        } catch (\Exception $e) {
            \Log::error('Error saving data:', [
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'An unexpected error occurred. Please try again later.']);
        }
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
