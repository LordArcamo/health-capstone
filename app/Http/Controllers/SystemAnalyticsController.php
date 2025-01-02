<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PersonalInformation;
use App\Models\VaccinationRecord;
use App\Models\CheckUp;
use App\Models\RiskManagement;
use Illuminate\Support\Facades\DB;

class SystemAnalyticsController extends Controller
{
    public function index(Request $request) 
    {
        $totalPatients = $this->totalPatients($request);
        $risk = $this->risk($request);
        $vaccinations = $this->vaccinations($request);
        $cases = $this->cases($request);
        
        $monthlyStats = $this->getPatientStatistics($request);
        $referredStats = $this->getReferredStats($request);
        $lineChart = $this->getVaccinationStatistics($request);
        $casesStats = $this->getCasesStatistics($request);
        $mentalHealthStats = $this->getMentalHealthStatistics($request);

        return Inertia::render('Analytics', [
            'totalPatients' => $totalPatients,
            'risk' => $risk,
            'vaccinations' => $vaccinations,
            'cases' => $cases,
            'barChart' => $monthlyStats,
            'pieChart' => $referredStats,
            'lineChart' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'data' => $lineChart,
            ],
            'casesData' => $casesStats,
            'mentalHealthStats' => $mentalHealthStats,
        ]);
    }

    private function totalPatients($request)
    {
        $query = PersonalInformation::query();
    
        if ($request->gender) {
            $query->where('sex', $request->gender);
        }

        if ($request->ageRange) {
            $ages = explode('-', $request->ageRange);
            if (count($ages) === 2) {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$ages[0]])
                      ->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$ages[1]]);
            } else {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [60]);
            }
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
    
        return $query->count();
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
        $monthlyStats = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $query = PersonalInformation::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month);

            if ($request->gender) {
                $query->where('sex', $request->gender);
            }

            if ($request->ageRange) {
                $ages = explode('-', $request->ageRange);
                if (count($ages) === 2) {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [$ages[0]])
                          ->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) <= ?', [$ages[1]]);
                } else {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, birthDate, CURDATE()) >= ?', [60]);
                }
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

            $monthlyStats[] = $query->count();
        }

        return $monthlyStats;
    }

    private function getReferredStats($request)
    {
        $query = CheckUp::query();

        if ($request->gender) {
            $query->whereHas('personalInformation', function($q) use ($request) {
                $q->where('sex', $request->gender);
            });
        }
    
        if ($request->date) {
            $today = now();
            switch ($request->date) {
                case '7days':
                    $query->where('consultationDate', '>=', now()->subDays(7))
                          ->where('consultationDate', '<=', now());
                    break;
                case '30days':
                    $query->where('consultationDate', '>=', now()->subDays(30))
                          ->where('consultationDate', '<=', now());
                    break;
                case 'year':
                    $query->whereYear('consultationDate', now()->year);
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

        // Clone the query before adding the Referral condition
        $baseQuery = clone $query;
        
        // Get referred count
        $referredCount = $query->where('modeOfTransaction', 'Referral')->count();
        
        // Get total count from the base query
        $totalCount = $baseQuery->count();
        
        return [
            'referred' => $referredCount,
            'notReferred' => $totalCount - $referredCount
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
    
    private function getCasesStatistics()
    {
        $monthlyCases = [];
        $currentYear = date('Y');
    
        for ($month = 1; $month <= 12; $month++) {
            $count = CheckUp::whereYear('consultationDate', $currentYear)
                ->whereMonth('consultationDate', $month)
                ->count();
            $monthlyCases[] = $count;
        }

        return $monthlyCases;
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
