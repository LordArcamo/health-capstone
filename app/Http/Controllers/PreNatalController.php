<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PreNatal;
use App\Models\PersonalInformation;
use App\Models\GeneralTrimester;
use Illuminate\Support\Facades\DB;


class PreNatalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = PersonalInformation::join('prenatal', 'personal_information.personalId', '=', 'prenatal.personalId')
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
            'prenatal.prenatalId',
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
            'prenatal.philhealthNo',
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
        ->distinct()
        ->get();

        return Inertia::render('Table/PreNatal', [
            'PRENATAL' => $data,
        ]);
    }

    public function fetchTrimesterData($prenatalId, $trimester)
    {
        try {
            // Fetch the latest data for this prenatalId and trimester
            $trimesterData = GeneralTrimester::where('prenatalId', $prenatalId)
                ->where('trimester', $trimester)
                ->latest('created_at')  // Order by latest created_at
                ->first();

            if ($trimesterData) {
                // Get the associated checkbox data based on trimester
                $checkboxData = null;
                switch($trimester) {
                    case '1':
                        $checkboxData = $trimesterData->checkbox1;
                        break;
                    case '2':
                        $checkboxData = $trimesterData->checkbox2;
                        break;
                    case '3':
                    case '4':
                    case '5':
                        $checkboxData = $trimesterData->checkbox3;
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
            return response()->json([
                'success' => false,
                'message' => 'Error fetching trimester data: ' . $e->getMessage()
            ], 500);
        }
    }
    

    public function import(Request $request)
    {
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
    
                // Insert data in PersonalInformation, excluding personalId
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
    
                // Insert data in Prenatal with the auto-incremented personalId
                Prenatal::create([
                    'personalId' => $personalInfo->personalId,
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
                    'menarche' => $prenatalData['menarche'],
                    'sexualOnset' => $prenatalData['sexualOnset'],
                    'periodDuration' => $prenatalData['periodDuration'],
                    'birthControl' => $prenatalData['birthControl'],
                    'intervalCycle' => $prenatalData['intervalCycle'],
                    'menopause' => $prenatalData['menopause'],
                    'lmp' => $prenatalData['lmp'],
                    'edc' => $prenatalData['edc'],
                    'gravidity' => $prenatalData['gravidity'],
                    'parity' => $prenatalData['parity'],
                    'term' => $prenatalData['term'],
                    'preterm' => $prenatalData['preterm'],
                    'abortion' => $prenatalData['abortion'],
                    'living' => $prenatalData['living'],
                    'syphilisResult' => $prenatalData['syphilisResult'],
                    'penicillin' => $prenatalData['penicillin'],
                    'hemoglobin' => $prenatalData['hemoglobin'],
                    'hematocrit' => $prenatalData['hematocrit'],
                    'urinalysis' => $prenatalData['urinalysis'],
                    'ttStatus' => substr($prenatalData['ttStatus'], 0, 50), // Truncate to fit the column size
                    'tdDate' => $prenatalData['tdDate'],
                ]);
                
            }
        });
    
        return redirect()->back()->with('success', 'Prenatal data imported successfully!');
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
