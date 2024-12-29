<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PersonalInformation;


class SystemAnalyticsController extends Controller
{
    public function index(Request $request) 
    {
        if($request->gender) {
            $genderCount = PersonalInformation::where('sex', $request->gender)->count();
            dd($genderCount);
        }

        $totalPatients = $this->totalPatients($request);
        $referredPatients = $this->referredPatients($request);
        $vaccinations = $this->vaccinations($request);
        $cases = $this->cases($request);
        return Inertia::render('Analytics', [
            'totalPatients' => $totalPatients,
            'referredPatients' => $referredPatients,
            'vaccinations' => $vaccinations,
            'cases' => $cases,
        ]);
    }

    private function totalPatients($request) 
    {

    }

    private function referredPatients($request) 
    {

    }

    private function vaccinations($request) 
    {

    }

    private function cases($request) 
    {

    }
}
