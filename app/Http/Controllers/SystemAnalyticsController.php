<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PersonalInformation;
use App\Models\VaccinationRecord;
use App\Models\CheckUp;
use App\Models\RiskManagement;


class SystemAnalyticsController extends Controller
{
    public function index(Request $request) 
    {
        if($request->gender) {
            $genderCount = PersonalInformation::where('sex', $request->gender)->count();
            dd($genderCount);
        }

        $totalPatients = $this->totalPatients($request);
        $risk = $this->risk($request);
        $vaccinations = $this->vaccinations($request);
        $cases = $this->cases($request);
        $monthlyStats = $this->getPatientStatistics();

        return Inertia::render('Analytics', [
            'totalPatients' => $totalPatients,
            'risk' => $risk,
            'vaccinations' => $vaccinations,
            'cases' => $cases,
            'barChart' => $monthlyStats,
        ]);
    }

    private function totalPatients($request)
    {
        $query = PersonalInformation::query();
    
        if ($request->gender) {
            $query->where('sex', $request->gender);
        }
    
        if ($request->date) {
            // Example: Filter by a specific date range
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        return $query->count();
    }

    private function risk($request) 
    {
        $query = RiskManagement::query();
    
        if ($request->gender) {
            $query->where('sex', $request->gender);
        }
    
        if ($request->date) {
            // Example: Filter by a specific date range
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        return $query->count();

    }

    private function vaccinations($request) 
    {
        $query = VaccinationRecord::query();

        if ($request->gender) {
            $query->where('sex', $request->gender);
        }
    
        if ($request->date) {
            // Example: Filter by a specific date range
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        return $query->count();
    }

    private function cases($request) 
    {
        $query = CheckUp::where('itr.visitType', 'Mental Health');

        if ($request->gender) {
            $query->where('sex', $request->gender);
        }
    
        if ($request->date) {
            // Example: Filter by a specific date range
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        return $query->count();
    }

    private function getPatientStatistics()
    {
        $monthlyStats = [];
        $currentYear = date('Y');

        for ($month = 1; $month <= 12; $month++) {
            $count = PersonalInformation::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
            $monthlyStats[] = $count;
        }

        return $monthlyStats;
    }
}
