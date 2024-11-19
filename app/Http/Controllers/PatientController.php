<?php

namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function search(Request $request)
        {
            $query = $request->input('query');
            
            $patients = PersonalInformation::where('lastName', 'like', "%$query%")
                ->orWhere('personalId', 'like', "%$query%")
                ->get(['personalId', 'firstName', 'lastName']);

            // Log fetched data for debugging
            \Log::info('Fetched patients:', $patients->toArray());

            // Return the data via Inertia
            return Inertia::render('Checkup', [
                'patients' => $patients, // Ensure the patients are passed here correctly
            ]);
        }

}
