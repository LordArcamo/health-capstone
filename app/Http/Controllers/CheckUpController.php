<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CheckUp;

class CheckUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $checkUps = CheckUp::all();
        return Inertia::render('Table/IndividualTreatmentRecord', [
            'checkUps' => $checkUps, // Pass check-up data to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CheckUp/IndividualTreatmentRecordCheckup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'middleName' => 'required|string|max:100',
            'suffix' => 'required|string|max:10', 
            'address' => 'required|string|max:255',
            'age' => 'required|numeric', 
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:15',
            'sex' => 'required|string|max:10', 
            'consultationDate' => 'required|date',
            'consultationTime' => 'required|date_format:H:i',
            'modeOfTransaction' => 'required|string|max:50',
            'bloodPressure' => 'required|string|max:20', 
            'temperature' => 'required|numeric|between:0,100', 
            'height' => 'required|numeric|between:0,300',
            'weight' => 'required|numeric|between:0,500', 
            'providerName' => 'required|string|max:100',
            'natureOfVisit' => 'required|string|max:100',
            'visitType' => 'required|string|max:50',
            'chiefComplaints' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'medication' => 'required|string|max:255',
        ]);
        

        // Create and fill the model in one step
        $checkUp = CheckUp::create($validatedData);

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
