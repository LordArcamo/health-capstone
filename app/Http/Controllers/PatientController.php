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

        // Fetch patients based on search query (name or ID)
        $patients  = PersonalInformation::where('lastName', 'like', "%$query%")
            ->orWhere('id', 'like', "%$query%")
            ->get();

        // Return patients data through Inertia
        return Inertia::render('WelcomeModal', [
            'patients ' => $patients ,
        ]);
    }
}
