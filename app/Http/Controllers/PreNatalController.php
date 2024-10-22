<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\PreNatal;

class PreNatalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Prenatal = PreNatal::all();
        return Inertia::render('Table/PreNatal', [
            'Prenatal' => $Prenatal, // Pass check-up data to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('CheckUp/PreNatalCheckup');
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
            'address' => 'required|string|max:255',
            'age' => 'required|numeric',
            'birthdate' => 'required|date',
            'modeOfTransaction' => 'required|string|max:255',
            'consultationDate' => 'required|date',
            'consultationTime' => 'required|date_format:H:i',
            'bloodPressure' => 'required|string|max:255',
            'temperature'  => 'required|numeric|between:0,999.99',
            'height' => 'required|numeric|between:0,999.99',
            'weight' => 'required|numeric|between:0,999.99',
            'providerName' => 'required|string|max:255',
            'nameOfSpouse' => 'required|string|max:255',
            'emergencyContact' => 'required|string|max:255',
            'fourMember' => 'required|string|max:255',
            'philhealthStatus' => 'required|string|max:255',
            'philhealthId' => 'nullable|string|max:255',
            'menarche' => 'required|string|max:255',
            'sexualOnset' => 'required|string|max:255',
            'periodDuration' => 'required|string|max:255',
            'birthControl' => 'required|string|max:255',
            'intervalCycle' => 'required|string|max:255',
            'menopause' => 'required|string|max:255',
            'lmp' => 'required|date',
            'edc' => 'required|date',
            'gravidity' => 'required|string|max:255',
            'parity' => 'required|string|max:255',
            'term' => 'required|string|max:255',
            'preterm' => 'required|string|max:255',
            'abortion' => 'required|string|max:255',
            'living' => 'required|string|max:255',
            'syphilisResult' => 'required|string|max:255',
            'penicillin' => 'required|string|max:255',
            'hemoglobin' => 'required|numeric|between:0,999.99',
            'hematocrit' => 'required|numeric|between:0,999.99',
            'urinalysis' => 'required|string|max:255',
            'ttStatus' => 'required|string|max:255',
            'tdDate' => 'required|date',
        ]);

        // Create a new instance of PreNatal
        $pre_natal = PreNatal::create($validatedData); // Use mass assignment

        // Redirect back with success message
        return redirect()->route('prenatal.store')->with('Success', 'Data saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
