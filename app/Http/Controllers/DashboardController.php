<?php
namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\ConsultationDetails;
use App\Models\VisitInformation;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // Temporarily increase memory limit
        ini_set('memory_limit', '512M'); // Adjust as needed, e.g., 1024M for 1GB or more

        // Fetch total patients count in real-time
        $totalPatients = PersonalInformation::select('consultationDate')->count();


        // Fetch referred patients count in real-time
        $referredPatientsCount = ConsultationDetails::join('personal_information', 'consultation_details.personalId', '=', 'personal_information.personalId')
            ->where('consultation_details.modeOfTransaction', 'Referral')
            ->distinct('personal_information.personalId')
            ->count('personal_information.personalId');


        // Fetch cases data for chart in real-time
        $casesData = VisitInformation::select('diagnosis', VisitInformation::raw('COUNT(*) as count'))
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

            $patientsWithCheckUp = DB::table('personal_information')
            ->join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
            ->select(
                'personal_information.personalId',
                'consultation_details.consultationDate as consultationDate', // ✅ Use correct date
                'consultation_details.modeOfTransaction'
            )

            ->unionAll(
                DB::table('personal_information')
                    ->join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
                    ->select(
                        'personal_information.personalId',
                        'prenatal_consultation_details.consultationDate as consultationDate', // ✅ Use correct date
                        DB::raw("'Prenatal' as modeOfTransaction")
                    )
            )

            ->unionAll(
                DB::table('personal_information')
                    ->join('national_immunization_programs', 'personal_information.personalId', '=', 'national_immunization_programs.personalId')
                    ->select(
                        'personal_information.personalId',
                        'national_immunization_programs.date as consultationDate', // ✅ Replace with actual date field
                        DB::raw("'Immunization' as modeOfTransaction")
                    )
            )

            ->unionAll(
                DB::table('personal_information')
                    ->join('vaccination_records', 'personal_information.personalId', '=', 'vaccination_records.personalId')
                    ->select(
                        'personal_information.personalId',
                        'vaccination_records.dateOfVisit as consultationDate', // ✅ Replace with actual date field
                        DB::raw("'Vaccination' as modeOfTransaction")
                    )
            )

            ->get()
            ->toArray();

              // Prepare data for chart of non-referred cases by diagnosis
   // Prepare data for chart of non-referred cases by diagnosis, age, gender, and month

              // Get the filter values from the request
              $selectedYear = $request->input('selectedYear');
              $selectedMonth = $request->input('selectedMonth');
              $selectedAgeRange = $request->input('selectedAgeRange');
              $selectedGender = $request->input('selectedGender');


              $nonReferredData = ConsultationDetails::join('personal_information', 'consultation_details.personalId', '=', 'personal_information.personalId')
              ->join('visit_information', 'consultation_details.consultationDetailsID', '=', 'visit_information.consultationDetailsID')
              ->where('consultation_details.modeOfTransaction', '!=', 'Referral') // Exclude referred cases
              ->select(
                  'visit_information.diagnosis', // Include diagnosis
                  DB::raw('MONTH(consultation_details.consultationDate) as month'), // Extract month from consultationDate
                  DB::raw('YEAR(consultation_details.consultationDate) as year'), // Extract year from consultationDate
                  DB::raw('COUNT(visit_information.visitInformationID) as case_count') // Count cases
              )
              ->whereYear('consultation_details.consultationDate', request('selectedYear', now()->year)) // Default to current year
              ->when(request('selectedMonth'), function ($query) {
                  return $query->whereMonth('consultation_details.consultationDate', request('selectedMonth'));
              })
              ->when(request('selectedAgeRange'), function ($query) {
                  $ageRange = explode('-', request('selectedAgeRange'));
                  return $query->whereBetween('personal_information.age', [$ageRange[0], $ageRange[1]]);
              })
              ->when(request('selectedGender'), function ($query) {
                  return $query->where('personal_information.sex', request('selectedGender'));
              })
              ->groupBy('visit_information.diagnosis', 'month', 'year') // Group by diagnosis, month, and year
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
