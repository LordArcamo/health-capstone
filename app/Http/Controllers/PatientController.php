<?php
namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PatientController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Fetch patients based on search query (name or ID)
        $patients = PersonalInformation::where('lastName', 'like', "%$query%")
            ->orWhere('personalId', 'like', "%$query%")
            ->get();


        // Return patients data through Inertia
        return Inertia::render('WelcomeModal', [
            'patients' => $patients,
        ]);
    }
}
