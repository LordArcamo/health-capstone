<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PersonalInformation;
use App\Models\VaccinationRecord;
use App\Models\CheckUp;
use App\Models\ConsultationDetails;
use App\Models\PreNatal;
use App\Models\VisitInformation;
use App\Models\RiskManagement;
use Illuminate\Support\Facades\DB;

class SystemAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $prenatal = PersonalInformation::join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
            ->join('prenatal_visit_information', 'prenatal_consultation_details.prenatalConsultationDetailsID', '=', 'prenatal_visit_information.prenatalConsultationDetailsID')
            ->leftJoin('general_trimester', 'prenatal_consultation_details.prenatalConsultationDetailsID', '=', 'general_trimester.prenatalConsultationDetailsID')
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
                'prenatal_visit_information.tdDate',
                'general_trimester.trimester',
            )
            ->distinct()
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
            'vaccinenatedPatients' => $vaccinenatedPatients,
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
            'prenatal' => $prenatal
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
        $query = ConsultationDetails::query();

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
            $count = VisitInformation::join('consultation_details', 'visit_information.consultationDetailsID', '=', 'consultation_details.consultationDetailsID')
                ->whereYear('consultation_details.consultationDate', $currentYear)
                ->whereMonth('consultation_details.consultationDate', $month)
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
