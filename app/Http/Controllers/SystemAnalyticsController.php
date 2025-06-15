<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PersonalInformation;
use App\Models\VaccinationRecord;
use App\Models\CheckUp;
use App\Models\ConsultationDetails;
use App\Models\NationalImmunizationProgram;
use App\Models\PrenatalConsultationDetails;
use App\Models\VisitInformation;
// use App\Models\RiskManagement;
use Illuminate\Support\Facades\DB;

class SystemAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $prenatal = PersonalInformation::join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
        ->leftJoin('general_trimester', function ($join) {
            $join->on('prenatal_consultation_details.prenatalConsultationDetailsID', '=', 'general_trimester.prenatalConsultationDetailsID')
                ->whereRaw('CAST(general_trimester.trimester AS UNSIGNED) BETWEEN 1 AND 5');
        })
        ->leftJoin('postpartum', 'prenatal_consultation_details.prenatalConsultationDetailsID', '=', 'postpartum.prenatalConsultationDetailsID')
        ->select(
            'personal_information.personalId',
            'prenatal_consultation_details.prenatalConsultationDetailsID',
            'personal_information.age',
            'personal_information.barangay', // Added Barangay
            DB::raw('
                COALESCE(
                    postpartum.deliveryDate, 
                    general_trimester.date_of_visit, 
                    prenatal_consultation_details.consultationDate
                ) as visit_date
            ')
        )
        ->where('prenatal_consultation_details.status', 'completed') // Only include completed records
        ->when($request->input('selectedBarangay') && $request->input('selectedBarangay') !== 'all', function ($query) use ($request) {
            $query->where('personal_information.barangay', '=', $request->input('selectedBarangay'));
        })
        ->groupBy(
            'personal_information.personalId',
            'prenatal_consultation_details.prenatalConsultationDetailsID',
            'personal_information.age',
            'personal_information.barangay',
            'visit_date'
        )
        ->orderBy('visit_date', 'asc')
        ->get();

        // Get vaccinated patients with their history
        $vaccinenatedPatients = PersonalInformation::with(['vaccinationRecords' => function($query) {
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


        $monthly = ConsultationDetails::with(['personalInformation', 'visitInformation'])
        ->whereHas('visitInformation') // Ensures only records with visitInformation are fetched
        ->get();

        $topBarangays = DB::table('consultation_details')
        ->join('visit_information', 'consultation_details.consultationDetailsID', '=', 'visit_information.consultationDetailsID')
        ->join('personal_information', 'consultation_details.personalId', '=', 'personal_information.personalId')
        ->select(
            'personal_information.barangay',
            DB::raw('COUNT(visit_information.consultationDetailsID) as total_cases'),
            DB::raw('GROUP_CONCAT(consultation_details.consultationDate ORDER BY consultation_details.consultationDate ASC SEPARATOR ", ") as all_consultations') 
        )
        ->groupBy('personal_information.barangay')
        ->orderByDesc('total_cases')
        ->limit(10)
        ->get();


        $totalPatients = $this->totalPatients($request);
        // $risk_management = $this->risk($request);
        $vaccinations = $this->vaccinations($request);
        $cases = $this->cases($request);

        $monthlyStats = $this->getPatientStatistics($request);
        $referredStats = $this->getReferredStats($request);
        $lineChart = $this->getVaccinationStatistics($request);
        $casesStats = $this->getCasesStatistics($request);
        $mentalHealthStats = $this->getMentalHealthStatistics($request);

        return Inertia::render('Analytics', [
            'vaccinenatedPatients' => $vaccinenatedPatients,
            'totalPatients' => $totalPatients,
            // 'risk' => $risk,
            'vaccinations' => $vaccinations,
            'cases' => $cases,
            'barChart' => $monthlyStats,
            'pieChart' => $this->getReferredStats(),
            'lineChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data' => $lineChart,
            ],
            'casesData' => $casesStats,
            'mentalHealthStats' => $mentalHealthStats,
            'prenatal' => $prenatal,
            'monthly' => $monthly,
            'topBarangays' => $topBarangays
        ]);
    }

    public function getTotalPatientsData(Request $request)
    {
        $year = $request->input('year');
        $gender = $request->input('gender');

        $query = DB::table('personal_information')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            );

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        if ($gender) {
            $query->where('sex', $gender);
        }

        $monthlyData = $query->groupBy('month')
            ->orderBy('month')
            ->get();

        // Initialize array with zeros for all months
        $data = array_fill(0, 12, 0);

        // Fill in actual counts
        foreach ($monthlyData as $item) {
            $data[$item->month - 1] = $item->count;
        }

        // Get available years
        $years = DB::table('personal_information')
            ->select(DB::raw('DISTINCT YEAR(created_at) as year'))
            ->orderBy('year', 'desc')
            ->pluck('year');

        return response()->json([
            'monthlyData' => $data,
            'years' => $years,
            'filters' => [
                'selectedYear' => $year,
                'selectedGender' => $gender
            ]
        ]);
    }

    private function totalPatients($request)
    {
        // Apply filters using closures
        $applyFilters = function ($query) use ($request) {
            if ($request->year) {
                $query->whereYear('consultation_details.consultationDate', $request->year);
            }
    
            if ($request->gender) {
                $query->where('personal_information.sex', $request->gender);
            }
        };
    
        $consultationCount = ConsultationDetails::join('personal_information', 'consultation_details.personalId', '=', 'personal_information.personalId')
            ->when($request, $applyFilters)
            ->count();
    
        $prenatalCount = PrenatalConsultationDetails::join('personal_information', 'prenatal_consultation_details.personalId', '=', 'personal_information.personalId')
            ->when($request, $applyFilters)
            ->count();
    
        $nipCount = NationalImmunizationProgram::join('personal_information', 'national_immunization_programs.personalId', '=', 'personal_information.personalId')
            ->when($request, $applyFilters)
            ->count();
    
        $vaccinationCount = VaccinationRecord::join('personal_information', 'vaccination_records.personalId', '=', 'personal_information.personalId')
            ->when($request, $applyFilters)
            ->count();
    
        return $consultationCount + $prenatalCount + $nipCount + $vaccinationCount;
    }
    


    private function risk($request)
    {
        $query = RiskManagement::query();

        if ($request->gender) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('sex', $request->gender);
            });
        }

        if ($request->date) {
            $today = now();
            switch ($request->date) {
                case '7days':
                    $query->where('created_at', '>=', $today->subDays(7));
                    break;
                case '30days':
                    $query->where('created_at', '>=', $today->subDays(30));
                    break;
                case 'year':
                    $query->whereYear('created_at', $today->year);
                    break;
            }
        }

        if ($request->ageRange) {
            $query->whereHas('patient', function($q) use ($request) {
                $ages = explode('-', $request->ageRange);
                if (count($ages) === 2) {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$ages[0]])
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$ages[1]]);
                } else {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [60]);
                }
            });
        }

        return $query->count();
    }

    private function vaccinations($request)
    {
        $query = VaccinationRecord::query();

        if ($request->gender) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('sex', $request->gender);
            });
        }

        if ($request->date) {
            $today = now();
            switch ($request->date) {
                case '7days':
                    $query->where('dateOfVisit', '>=', $today->subDays(7));
                    break;
                case '30days':
                    $query->where('dateOfVisit', '>=', $today->subDays(30));
                    break;
                case 'year':
                    $query->whereYear('dateOfVisit', $today->year);
                    break;
            }
        }

        if ($request->ageRange) {
            $query->whereHas('patient', function($q) use ($request) {
                $ages = explode('-', $request->ageRange);
                if (count($ages) === 2) {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$ages[0]])
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$ages[1]]);
                } else {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [60]);
                }
            });
        }

        return $query->count();
    }

    private function cases($request)
    {
        $query = CheckUp::where('visitType', 'Mental Health');

        if ($request->gender) {
            $query->whereHas('personalInformation', function($q) use ($request) {
                $q->where('sex', $request->gender);
            });
        }

        if ($request->date) {
            $today = now();
            switch ($request->date) {
                case '7days':
                    $query->where('consultationDate', '>=', $today->subDays(7));
                    break;
                case '30days':
                    $query->where('consultationDate', '>=', $today->subDays(30));
                    break;
                case 'year':
                    $query->whereYear('consultationDate', $today->year);
                    break;
            }
        }

        if ($request->ageRange) {
            $query->whereHas('personalInformation', function($q) use ($request) {
                $ages = explode('-', $request->ageRange);
                if (count($ages) === 2) {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$ages[0]])
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$ages[1]]);
                } else {
                    $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [60]);
                }
            });
        }

        if ($request->casesType) {
            switch ($request->casesType) {
                case 'confirmed':
                    $query->whereNotNull('diagnosis');
                    break;
                case 'recovered':
                    $query->where('natureOfVisit', 'Follow-up')
                          ->where('diagnosis', 'like', '%Recovered%');
                    break;
                case 'deaths':
                    $query->where('diagnosis', 'like', '%Deceased%');
                    break;
            }
        }

        return $query->count();
    }

    private function getPatientStatistics($request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $today = now();
    
        // Handle custom date range
        if ($startDate && $endDate) {
            // Ensure the startDate and endDate are in proper date format
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
    
            // Queries filtered by startDate and endDate
            $consultationCount = DB::table('consultation_details')
                ->whereBetween('consultationDate', [$startDate, $endDate])
                ->count();
    
            $prenatalCount = DB::table('prenatal_consultation_details')
                ->whereBetween('consultationDate', [$startDate, $endDate])
                ->count();
    
            $immunizationCount = DB::table('national_immunization_programs')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
    
            $vaccinationCount = DB::table('vaccination_records')
                ->whereBetween('dateOfvisit', [$startDate, $endDate])
                ->count();
    
            // Return combined count for custom date range (e.g., for "custom" category in chart)
            return [$consultationCount + $prenatalCount + $immunizationCount + $vaccinationCount];
        }
    
        // Default: Monthly breakdown
        $monthlyStats = [];
        $currentYear = date('Y');
    
        for ($month = 1; $month <= 12; $month++) {
            // Handle custom date range filtering if provided
            $consultationQuery = DB::table('consultation_details')
                ->whereYear('consultationDate', $currentYear)
                ->whereMonth('consultationDate', $month);
        
            $prenatalQuery = DB::table('prenatal_consultation_details')
                ->whereYear('consultationDate', $currentYear)
                ->whereMonth('consultationDate', $month);
        
            $immunizationQuery = DB::table('national_immunization_programs')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month);
        
            $vaccinationQuery = DB::table('vaccination_records')
                ->whereYear('dateOfvisit', $currentYear)
                ->whereMonth('dateOfvisit', $month);
        
            // Apply date range filter if startDate and endDate are provided
            if ($startDate && $endDate) {
                $consultationQuery->whereBetween('consultationDate', [$startDate, $endDate]);
                $prenatalQuery->whereBetween('consultationDate', [$startDate, $endDate]);
                $immunizationQuery->whereBetween('created_at', [$startDate, $endDate]);
                $vaccinationQuery->whereBetween('dateOfvisit', [$startDate, $endDate]);
            }
        
            // Add monthly stats to the array
            $monthlyStats[] =
                $consultationQuery->count() +
                $prenatalQuery->count() +
                $immunizationQuery->count() +
                $vaccinationQuery->count();
        }
    
        return $monthlyStats;
    }
    


    private function getReferredStats()
    {
        // Count total referred and not referred
        $totalReferred = ConsultationDetails::where('modeOfTransaction', 'Referral')->count();
        $totalNotReferred = ConsultationDetails::where('modeOfTransaction', '!=', 'Referral')->count();
    
        // Get breakdown of referrals by reason
        $reasonsBreakdown = ConsultationDetails::select(
            'reasonsForReferral', // Ensure correct column name
            DB::raw('SUM(CASE WHEN modeOfTransaction = "Referral" THEN 1 ELSE 0 END) as referred'),
            DB::raw('SUM(CASE WHEN modeOfTransaction != "Referral" THEN 1 ELSE 0 END) as notReferred')
        )
        ->groupBy('reasonsForReferral')
        ->get()
        ->mapWithKeys(function ($item) {
            return [
                $item->reasonsForReferral => [
                    'referred' => $item->referred ?? 0, 
                    'notReferred' => $item->notReferred ?? 0 
                ]
            ];
        })
        ->toArray();
    
        // Ensure the structure always includes total counts
        return [
            'referred' => $totalReferred ?? 0,
            'notReferred' => $totalNotReferred ?? 0,
            'reasons' => $reasonsBreakdown
        ];
    }    
    
    private function getVaccinationStatistics($request)
    {
        $monthlyVaccinations = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $query = VaccinationRecord::whereYear('dateOfVisit', $currentYear)
                ->whereMonth('dateOfVisit', $month);

            if ($request->gender) {
                $query->whereHas('patient', function($q) use ($request) {
                    $q->where('sex', $request->gender);
                });
            }

            if ($request->date) {
                switch ($request->date) {
                    case '7days':
                        $query->where('dateOfVisit', '>=', now()->subDays(7))
                              ->where('dateOfVisit', '<=', now());
                        break;
                    case '30days':
                        $query->where('dateOfVisit', '>=', now()->subDays(30))
                              ->where('dateOfVisit', '<=', now());
                        break;
                    case 'year':
                        $query->whereYear('dateOfVisit', now()->year);
                        break;
                }
            }

            if ($request->ageRange) {
                $query->whereHas('patient', function($q) use ($request) {
                    $ages = explode('-', $request->ageRange);
                    if (count($ages) === 2) {
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$ages[0]])
                          ->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$ages[1]]);
                    } else {
                        $q->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [60]);
                    }
                });
            }

            $monthlyVaccinations[] = $query->count();
        }

        return $monthlyVaccinations;
    }

    private function getCasesStatistics($request)
    {
        $monthlyStats = array_fill(0, 12, 0); // Initialize all months to zero
        $currentYear = date('Y');

        // Fetch only records where `visit_information` exists and join necessary tables
        $cases = VisitInformation::join('consultation_details', 'visit_information.consultationDetailsID', '=', 'consultation_details.consultationDetailsID')
            ->join('personal_information', 'consultation_details.personalId', '=', 'personal_information.personalId')
            ->whereYear('consultation_details.consultationDate', $currentYear)
            ->select(
                'visit_information.diagnosis',
                'consultation_details.consultationDate',
                'personal_information.sex',
                DB::raw('TIMESTAMPDIFF(YEAR, personal_information.birthdate, CURDATE()) as age')
            );

        // ✅ Apply Filters
        if ($request->gender) {
            $cases->where('personal_information.sex', $request->gender);
        }

        if ($request->ageRange) {
            $ages = explode('-', $request->ageRange);
            if (count($ages) === 2) {
                $cases->whereRaw('TIMESTAMPDIFF(YEAR, personal_information.birthdate, CURDATE()) BETWEEN ? AND ?', [$ages[0], $ages[1]]);
            } else {
                $cases->whereRaw('TIMESTAMPDIFF(YEAR, personal_information.birthdate, CURDATE()) >= ?', [$ages[0]]);
            }
        }

        // Fetch Only Valid Data
        $cases = $cases->get();

        // ✅ Group by Month
        foreach ($cases as $case) {
            $monthIndex = date('n', strtotime($case->consultationDate)) - 1;
            $monthlyStats[$monthIndex]++;
        }

        return [
            'cases' => array_values($monthlyStats),
        ];
    }

    

    private function getMentalHealthStatistics()
    {
        $stats = CheckUp::select('diagnosis', DB::raw('COUNT(*) as total'))
            ->where('visitType', 'Mental Health')
            ->whereNotNull('diagnosis')
            ->groupBy('diagnosis')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->diagnosis => $item->total];
            })
            ->toArray();

        return [
            'labels' => array_keys($stats),
            'data' => array_values($stats)
        ];
    }

}
