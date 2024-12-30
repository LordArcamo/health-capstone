<?php
namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CheckUp;



class PatientController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        \Log::debug("Search query: " . $query); // Log query to check if it's coming through

        $patients = PersonalInformation::where('firstName', 'like', "%$query%")
            ->orWhere('lastName', 'like', "%$query%")
            ->orWhere('personalId', 'like', "%$query%")
            ->get();

        return Inertia::render('Checkup', [
            'patients' => $patients ?? [],
        ]);
    }
    public function index()
    {
        // Temporarily increase memory limit
        ini_set('memory_limit', '512M'); // Adjust as needed, e.g., 1024M for 1GB or more
    
        // Fetch total patients count in real-time
        $totalPatients = PersonalInformation::count();
    
        // Fetch referred patients count in real-time
        $referredPatientsCount = CheckUp::where('modeOfTransaction', 'Referral')->count();
    
        // Fetch cases data for chart in real-time
        $casesData = CheckUp::select('diagnosis', CheckUp::raw('COUNT(*) as count'))
            ->whereNotNull('diagnosis') // Exclude null diagnoses
            ->groupBy('diagnosis')
            ->get()
            ->map(function ($item) {
                return [
                    'diagnosis' => $item->diagnosis,
                    'count' => $item->count,
                ];
            })
            ->toArray();
    
        // Fetch patients with check-up data in real-time
        $patientsWithCheckUp = PersonalInformation::leftJoin('itr', 'personal_information.personalId', '=', 'itr.personalId')
            ->select(
                'personal_information.personalId',
                'personal_information.created_at',
                'itr.modeOfTransaction'
            )
            ->distinct() // Avoid duplicates caused by JOIN
            ->get()
            ->toArray();
    
        // Return data to the frontend
        return Inertia::render('Dashboard', [
            'casesData' => $casesData, // Diagnosis and counts
            'totalPatients' => $totalPatients, // Total patients count
            'referredPatients' => $referredPatientsCount, // Referred patients count
            'patients' => $patientsWithCheckUp, // All patients with check-up data
        ]);
    }

    public function disease(Request $request)
    {
        $diagnosis = $request->input('diagnosis', ''); // Retrieve the diagnosis filter from the request, default is empty
    
        // Use LEFT JOIN to fetch cases filtered by diagnosis if provided
        $cases = PersonalInformation::leftJoin('itr', 'personal_information.personalId', '=', 'itr.personalId')
            ->select(
                'personal_information.personalId',
                'personal_information.created_at',
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
                'itr.modeOfTransaction',
                'itr.consultationDate',
                'itr.consultationTime',
                'itr.diagnosis',
                'itr.chiefComplaints',
                'itr.medication',
                'itr.providerName',
                'itr.natureOfVisit',
                'itr.visitType'
            )
            ->when(!empty($diagnosis), function ($query) use ($diagnosis) {
                $query->where('itr.diagnosis', 'LIKE', "%$diagnosis%");
            })
            ->distinct() // Ensure no duplicate rows
            ->get();
    
        // Return the filtered cases to the frontend
        return Inertia::render('Table/DiseaseCases', [
            'cases' => $cases, // Pass the filtered cases
            'diagnosisFilter' => $diagnosis, // Current diagnosis filter for UI
        ]);
    }
    
    
    

    public function show()
    {
        // Use LEFT JOIN to include diagnosis and other fields
        $patients = PersonalInformation::leftJoin('itr', 'personal_information.personalId', '=', 'itr.personalId')
            ->select(
                'personal_information.personalId',
                'personal_information.created_at',
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
                'itr.modeOfTransaction' ,
                'itr.diagnosis' // Include diagnosis field
            )
            ->distinct() // Ensure no duplicate rows
            ->get()
            ->toArray();
        

        return Inertia::render('Table/Patient', [
            'totalPatients' => $patients, // Pass the joined data to the frontend
        ]);
    }

    public function showReferred(){
        $referredPatients = PersonalInformation::join('itr', 'personal_information.personalId', '=', 'itr.personalId')
        ->where('itr.modeOfTransaction', 'Referral')
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
            'itr.modeOfTransaction',
            'itr.referredFrom',
            'itr.referredTo',
            'itr.reasonsForReferral',
            'itr.referredBy',
        )
        ->get();

        return Inertia::render('Table/ReferredPatient', [
            'referredPatients' => $referredPatients,
        ]);
        
    }
    


}

