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
use App\Models\Prescription;

class CheckUpController extends Controller
{

    public function import(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data);

        DB::transaction(function () use ($data, $header) {
            foreach ($data as $row) {
                $patientData = array_combine($header, $row);

                // Format consultationTime
                if (!empty($patientData['consultationTime'])) {
                    $patientData['consultationTime'] = date('H:i:s', strtotime($patientData['consultationTime']));
                }

                // Fix dates
                $patientData['birthdate'] = !empty($patientData['birthdate']) ? date('Y-m-d', strtotime($patientData['birthdate'])) : null;
                $patientData['consultationDate'] = !empty($patientData['consultationDate']) ? date('Y-m-d', strtotime($patientData['consultationDate'])) : null;

                // Format numbers
                $patientData['temperature'] = isset($patientData['temperature']) ? number_format((float)$patientData['temperature'], 2, '.', '') : null;
                $patientData['height'] = isset($patientData['height']) ? number_format((float)$patientData['height'], 2, '.', '') : null;
                $patientData['weight'] = isset($patientData['weight']) ? number_format((float)$patientData['weight'], 2, '.', '') : null;
                $patientData['age'] = isset($patientData['age']) ? (int)$patientData['age'] : null;

                // Normalize status
                $statusRaw = strtolower(trim($patientData['status'] ?? ''));
                $isCancelled = $statusRaw === 'cancelled';
                $isCompleted = $statusRaw === 'completed';
                $patientData['status'] = in_array($statusRaw, ['cancelled', 'completed']) ? $statusRaw : 'in queue';
                $completedAt = $isCompleted ? now() : null;

                // Clean blood pressure
                $patientData['bloodPressure'] = isset($patientData['bloodPressure'])
                    ? preg_replace('/[^0-9\/]/', '', $patientData['bloodPressure'])
                    : null;

                // Lab test normalization (Yes/No)
                $patientData['requireLabTest'] = ucfirst(strtolower(trim($patientData['requireLabTest'] ?? 'No')));
                $requiresLab = $patientData['requireLabTest'] === 'Yes';

                // Parse selectedLabTests into JSON array
                if (!empty($patientData['selectedLabTests']) && $requiresLab) {
                    $patientData['selectedLabTests'] = json_encode(array_map('trim', explode(',', trim($patientData['selectedLabTests'], '[]"'))));
                } else {
                    $patientData['selectedLabTests'] = json_encode([]);
                }

                // Conditional logic: cancelled or lab test required
                if ($isCancelled) {
                    $patientData['chiefComplaints'] = 'None';
                    $patientData['diagnosis'] = 'None';
                    $patientData['medication'] = null;
                    $patientData['dosage'] = null;
                    $patientData['frequency'] = null;
                    $patientData['duration'] = null;
                    $patientData['notes'] = null;
                    $patientData['requireLabTest'] = 'No';
                    $patientData['selectedLabTests'] = json_encode([]);
                } elseif ($requiresLab) {
                    $patientData['diagnosis'] = 'None';
                    $patientData['medication'] = null;
                    $patientData['dosage'] = null;
                    $patientData['frequency'] = null;
                    $patientData['duration'] = null;
                    $patientData['notes'] = null;
                }

                // Validation
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
                    'referredFrom' => 'nullable|string|max:255',
                    'referredTo' => 'nullable|string|max:255',
                    'reasonsForReferral' => 'nullable|string|max:255',
                    'referredBy' => 'nullable|string|max:255',
                    'requireLabTest' => 'nullable|string|max:3',
                    'selectedLabTests' => 'nullable|json',
                    'status' => 'nullable|string|max:50',
                ]);

                if (!$isCancelled && !$requiresLab) {
                    $validator->addRules([
                        'chiefComplaints' => 'required|string|max:255',
                        'diagnosis' => 'required|string|max:255',
                        'medication' => 'required|string|max:255',
                        'dosage' => 'nullable|string|max:50',
                        'frequency' => 'nullable|string|max:50',
                        'duration' => 'nullable|string|max:50',
                        'notes' => 'nullable|string',
                    ]);
                }

                if ($validator->fails()) {
                    \Log::error('Validation failed: ' . json_encode($validator->errors()->all()));
                    throw new \Exception('Validation failed for row: ' . json_encode($patientData));
                }

                try {
                    // Create Personal Info
                    $personalInfo = PersonalInformation::create([
                        'firstName' => $patientData['firstName'],
                        'lastName' => $patientData['lastName'],
                        'middleName' => $patientData['middleName'] ?? null,
                        'suffix' => $patientData['suffix'] ?? null,
                        'purok' => $patientData['purok'] ?? null,
                        'barangay' => $patientData['barangay'] ?? null,
                        'age' => $patientData['age'],
                        'birthdate' => $patientData['birthdate'] ?? null,
                        'contact' => $patientData['contact'] ?? null,
                        'sex' => $patientData['sex'],
                    ]);

                    // Create Consultation Details
                    $consultationDetails = ConsultationDetails::create([
                        'personalId' => $personalInfo->personalId,
                        'id' => auth()->id(),
                        'consultationDate' => $patientData['consultationDate'],
                        'consultationTime' => $patientData['consultationTime'],
                        'modeOfTransaction' => $patientData['modeOfTransaction'],
                        'bloodPressure' => $patientData['bloodPressure'] ?? null,
                        'temperature' => $patientData['temperature'] ?? null,
                        'height' => $patientData['height'] ?? null,
                        'weight' => $patientData['weight'] ?? null,
                        'providerName' => $patientData['providerName'],
                        'natureOfVisit' => $patientData['natureOfVisit'],
                        'visitType' => $patientData['visitType'],
                        'referredFrom' => $patientData['referredFrom'] ?? 'None',
                        'referredTo' => $patientData['referredTo'] ?? 'None',
                        'reasonsForReferral' => $patientData['reasonsForReferral'] ?? 'None',
                        'referredBy' => $patientData['referredBy'] ?? 'None',
                        'status' => $patientData['status'],
                        'completed_at' => $completedAt,
                    ]);

                    // Always create VisitInformation
                    $visitInfo = VisitInformation::create([
                        'consultationDetailsID' => $consultationDetails->consultationDetailsID,
                        'id' => auth()->id(),
                        'chiefComplaints' => $patientData['chiefComplaints'],
                        'diagnosis' => $patientData['diagnosis'],
                        'requireLabTest' => $patientData['requireLabTest'],
                        'selectedLabTests' => $patientData['selectedLabTests'],
                    ]);

                    // Only create prescription if not cancelled and lab not required
                    if (!$isCancelled && !$requiresLab) {
                        Prescription::create([
                            'visitInformationID' => $visitInfo->visitInformationID,
                            'medication' => $patientData['medication'],
                            'dosage' => $patientData['dosage'] ?? '',
                            'frequency' => $patientData['frequency'] ?? '',
                            'duration' => $patientData['duration'] ?? '',
                            'notes' => $patientData['notes'] ?? '',
                        ]);
                    }

                } catch (\Exception $e) {
                    \Log::error('Error inserting data: ' . $e->getMessage());
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
                ->leftJoin('prescriptions', 'visit_information.visitInformationID', '=', 'prescriptions.visitInformationID')
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
                    DB::raw("
                        CASE
                            WHEN consultation_details.status = 'cancelled' THEN 'Cancelled'
                            WHEN visit_information.consultationDetailsID IS NULL THEN 'In Queue'
                            ELSE 'Completed'
                        END AS status
                    "),
                    'visit_information.consultationDetailsID',
                    'visit_information.chiefComplaints',
                    'visit_information.diagnosis',
                    DB::raw('GROUP_CONCAT(
                        CASE WHEN prescriptions.medication IS NOT NULL 
                        THEN prescriptions.medication 
                        ELSE "" 
                        END
                        ORDER BY prescriptions.prescriptionID SEPARATOR ";;") as medications'),
                    DB::raw('GROUP_CONCAT(
                        CASE WHEN prescriptions.dosage IS NOT NULL 
                        THEN prescriptions.dosage 
                        ELSE "" 
                        END
                        ORDER BY prescriptions.prescriptionID SEPARATOR ";;") as dosages'),
                    DB::raw('GROUP_CONCAT(
                        CASE WHEN prescriptions.frequency IS NOT NULL 
                        THEN prescriptions.frequency 
                        ELSE "" 
                        END
                        ORDER BY prescriptions.prescriptionID SEPARATOR ";;") as frequencies'),
                    DB::raw('GROUP_CONCAT(
                        CASE WHEN prescriptions.duration IS NOT NULL 
                        THEN prescriptions.duration 
                        ELSE "" 
                        END
                        ORDER BY prescriptions.prescriptionID SEPARATOR ";;") as durations'),
                    DB::raw('GROUP_CONCAT(
                        CASE WHEN prescriptions.notes IS NOT NULL 
                        THEN prescriptions.notes 
                        ELSE "" 
                        END
                        ORDER BY prescriptions.prescriptionID SEPARATOR ";;") as prescription_notes')
                )
                ->groupBy(
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
                    'consultation_details.status',
                    'visit_information.consultationDetailsID',
                    'visit_information.chiefComplaints',
                    'visit_information.diagnosis',
                    'visit_information.visitInformationID'
                )
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

        $doctors = User::where('role', 'doctor')
            ->where('status', 'active') // 👈 Only active doctors
            ->select('id', 'first_name', 'middle_name', 'last_name')
            ->get()
            ->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'fullName' => trim("Dr. {$doc->first_name} {$doc->middle_name} {$doc->last_name}"),
                ];
            });

        return Inertia::render('CheckUp/IndividualTreatmentRecordCheckup', [
            'personalInfo' => $personalInfo,
            'natureOfVisit' => $natureOfVisit,
            'doctors' => $doctors,
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
