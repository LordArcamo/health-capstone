<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\NationalImmunizationProgram;

class NationalImmunizationProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Immunization = NationalImmunizationProgram::all();
        return Inertia::render('Table/NationalImmunization', [
            'Immunization' => $Immunization, // Pass immunization data to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CheckUp/NationalImmunizationCheckup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'required|string|max:255',
            'suffix' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'age' => 'required|numeric',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'birthplace' => 'required|string|max:255',
            'bloodtype' => 'required|string|max:255',
            'mothername' => 'required|string|max:255',
            'dswdNhts' => 'required|string|max:255',
            'facilityHouseholdno' => 'required|string|max:255',
            'houseHoldno' => 'required|string|max:255',
            'fourpsmember' => 'required|string|max:255',
            'PCBMember' => 'required|string|max:255',
            'philhealthMember' => 'required|string|max:255',
            'statusType' => 'required|string|max:255',
            'philhealthNo' => 'required|string|max:255',
            'ifMember' => 'required|string|max:255',
            'familyMember' => 'required|string|max:255',
            'ttstatus' => 'required|string|max:255',
            'dateAssesed' => 'required|date',
            'date' => 'required|date',
            'place' => 'required|string|max:255',
            'guardian' => 'required|string|max:255',
        ]);

        // Create a new instance and fill it with validated data
        $nationalImmunizationProgram = NationalImmunizationProgram::create($validatedData);

        // Redirect back with success message
        return back()->with('Success', 'Data saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Logic to show a specific resource
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Logic to show edit form for a specific resource
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Logic to update a specific resource
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Logic to delete a specific resource
    }
}
