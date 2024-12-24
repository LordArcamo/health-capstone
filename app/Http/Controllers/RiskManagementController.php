<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use Inertia\Inertia;

class RiskManagementController extends Controller
{
    public function index()
    {
        $patients = PersonalInformation::all(); // Retrieve all patients
        return Inertia::render('Services/RiskManagement', [
            'patients' => $patients,
        ]);
    }

    public function store(Request $request)
    {
        // Logic to store a new risk assessment
        // Validate and save the data
        return redirect()->route('risk-management.index');
    }
    
    public function search(Request $request)
    {
        $query = $request->get('query');
        \Log::debug("Search query: " . $query); // Log query to check if it's coming through

        // Trim and lower case the query for more flexible matching
        $query = trim($query);
        $queryLower = strtolower($query);

        $patients = PersonalInformation::where(function($q) use ($queryLower) {
            $q->whereRaw("LOWER(firstName) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(lastName) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(personalId) LIKE ?", ["%$queryLower%"])
            ->orWhereRaw("LOWER(CONCAT(firstName, ' ', lastName)) LIKE ?", ["%$queryLower%"]); // Search by full name
        })->get();

        return Inertia::render('Services/RiskManagement', [
            'patients' => $patients ?? [],
        ]);
    }

    public function show()
    {
        $patients = PersonalInformation::all(); // Replace with your query to get patients data

        return Inertia::render('PatientTable', [
            'patients' => $patients,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Logic to update an existing risk assessment
        // Validate and update the data
        return redirect()->route('risk-management.index');
    }

    public function destroy($id)
    {
        // Logic to delete a risk assessment
        return redirect()->route('risk-management.index');
    }
}
