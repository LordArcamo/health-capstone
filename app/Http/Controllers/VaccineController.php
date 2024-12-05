<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use Inertia\Inertia;


class VaccineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // $personalId = $request->get('patient_personalId');

        // if ($personalId === 'new') {
        //     return Inertia::render('Services/Vaccination', [
        //         'personalInfo' => null, // No existing patient
        //     ]);
        // }

        // $personalInfo = $personalId ? PersonalInformation::find($personalId) : null;

        // if (!$personalInfo) {
        //     return back()->withErrors(['error' => 'Patient not found']);
        // }

        // return Inertia::render('Services/Vaccination', [
        //     'personalInfo' => $personalInfo,
        // ]);
        $patients = PersonalInformation::all(); // Fetch all patients

        return Inertia::render('Services/Vaccination', [
            'patients' => $patients, // Pass patients to the page
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');

        $patients = PersonalInformation::where('firstName', 'like', "%$query%")
                            ->orWhere('lastName', 'like', "%$query%")
                            ->orWhere('personalId', 'like', "%$query%")
                            ->get();
    
        return Inertia::render('Services/Vaccination', [
            'patients' => $patients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $patients = PersonalInformation::all(); // Replace with your query to get patients data

        return Inertia::render('PatientTable', [
            'patients' => $patients,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
