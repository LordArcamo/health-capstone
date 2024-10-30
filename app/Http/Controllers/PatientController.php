<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Fetch patients based on search query (name or ID)
        $patients = Patient::where('name', 'like', "%$query%")
            ->orWhere('id', 'like', "%$query%")
            ->get();

        // Return patients data through Inertia
        return Inertia::render('WelcomeModal', [
            'patients' => $patients,
        ]);
    }
}
