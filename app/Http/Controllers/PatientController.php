<?php

namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function search(Request $request)
    {
        // Get the query from the request
        $query = $request->input('query');

        // Fetch patients from the database based on the search query
        $patients = PersonalInformation::where('lastName', 'like', "%$query%")
            ->orWhere('personalId', 'like', "%$query%")
            ->get(['personalId', 'firstName', 'lastName']); // Only select personalId, firstName, and lastName

        // Return patients data via Inertia
        return Inertia::render('Checkup', [
            'patients' => $patients, // Return the filtered patients
        ]);
    }
}
