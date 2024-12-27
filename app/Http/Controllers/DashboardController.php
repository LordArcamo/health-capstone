<?php
namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CheckUp;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{

    public function index(Request $request)
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
        ->distinct()
        ->get()
        ->toArray();
    
              // Prepare data for chart of non-referred cases by diagnosis
   // Prepare data for chart of non-referred cases by diagnosis, age, gender, and month

              // Get the filter values from the request
              $selectedYear = $request->input('selectedYear');
              $selectedMonth = $request->input('selectedMonth');
              $selectedAgeRange = $request->input('selectedAgeRange');
              $selectedGender = $request->input('selectedGender');
          
          
              $nonReferredData = CheckUp::join('personal_information', 'itr.personalId', '=', 'personal_information.personalId')
              ->where('itr.modeOfTransaction', '!=', 'Referral') // Exclude referred cases
              ->select(
                  'itr.diagnosis', // Include diagnosis
                  DB::raw('MONTH(itr.consultationDate) as month'), // Extract month from consultationDate
                  DB::raw('YEAR(itr.consultationDate) as year'), // Extract year from consultationDate
                  DB::raw('COUNT(itr.itrId) as case_count') // Count cases
              )
              ->when(request('selectedYear'), function ($query) {
                  return $query->whereYear('itr.consultationDate', request('selectedYear'));
              })
              ->when(request('selectedMonth'), function ($query) {
                  return $query->whereMonth('itr.consultationDate', request('selectedMonth'));
              })
              ->when(request('selectedAgeRange'), function ($query) {
                  $ageRange = explode('-', request('selectedAgeRange'));
                  return $query->whereBetween('personal_information.age', [$ageRange[0], $ageRange[1]]);
              })
              ->when(request('selectedGender'), function ($query) {
                  return $query->where('personal_information.sex', request('selectedGender'));
              })
              ->groupBy('itr.diagnosis', 'month', 'year') // Group by diagnosis, month, and year
              ->orderBy('month')
              ->get()
              ->toArray();
          
   
        // Return data to the frontend
        return Inertia::render('Dashboard', [
            'casesData' => $casesData, // Diagnosis and counts
            'totalPatients' => $totalPatients, // Total patients count
            'nonReferredData' => $nonReferredData ?? [],
            'referredPatients' => $referredPatientsCount, // Referred patients count
            'patients' => $patientsWithCheckUp, // All patients with check-up data
        ]);
    }
    



}

