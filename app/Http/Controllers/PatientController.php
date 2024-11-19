<?php
namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;


class PatientController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        \Log::debug("Search query: " . $query); // Log query to check if it's coming through
    
        $patients = PersonalInformation::where('firstName', 'like', "%$query%")
                            ->orWhere('lastName', 'like', "%$query%")
                            ->orWhere('personalId', 'like', "%$query%")
                            ->get();
    
        return Inertia::render('Checkup', [
            'patients' => $patients ?? [],
        ]);
    }

}
    
