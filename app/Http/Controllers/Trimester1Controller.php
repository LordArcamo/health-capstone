<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Trimester1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'prenatalId' => 'required|integer',
            'date_of_visit' => 'required|date',
            'weight' => 'required|numeric',
            'bp' => 'required|string|max:20',
            'heart_rate' => 'required|numeric',
            'aog_months' => 'required|integer',
            'aog_days' => 'required|integer',
            'trimester' => 'required|string|max:50',
            
            // CheckBox1 data validation
            'prenatal_checkup' => 'required|boolean',
            'pe_done' => 'required|boolean',
            'prenatal_record' => 'required|boolean',
            'birth_plan_done' => 'required|boolean',
            'nkfda' => 'required|boolean',
            'health_teachings' => 'required|boolean',
            'referred_for' => 'required|boolean',
            'healthy_diet' => 'required|boolean',
            'fes04_folic' => 'required|boolean',
            'folic_acid' => 'nullable|integer',
            'fhb' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:50',
            'presentation' => 'nullable|string|max:50',
            'fundal_height' => 'nullable|string|max:50',
        ]);

        // Split validated data for GeneralTrimester
        $generalTrimesterData = [
            'prenatalId' => $validatedData['prenatalId'],
            'date_of_visit' => $validatedData['date_of_visit'],
            'weight' => $validatedData['weight'],
            'bp' => $validatedData['bp'],
            'heart_rate' => $validatedData['heart_rate'],
            'aog_months' => $validatedData['aog_months'],
            'aog_days' => $validatedData['aog_days'],
            'trimester' => $validatedData['trimester'],
        ];

        // Save general trimester data to GeneralTrimester table
        $generalTrimester = GeneralTrimester::create($generalTrimesterData);

        // Prepare data for CheckBox1 table
        $checkBox1Data = [
            'generalTrimesterID' => $generalTrimester->generalTrimesterID,
            'prenatal_checkup' => $validatedData['prenatal_checkup'] ?? false, // Defaults to false if not checked
            'pe_done' => $validatedData['pe_done'] ?? false,
            'prenatal_record' => $validatedData['prenatal_record'] ?? false,
            'birth_plan_done' => $validatedData['birth_plan_done'] ?? false,
            'nkfda' => $validatedData['nkfda'] ?? false,
            'health_teachings' => $validatedData['health_teachings'] ?? false,
            'referred_for' => $validatedData['referred_for'] ?? false,
            'healthy_diet' => $validatedData['healthy_diet'] ?? false,
            'fes04_folic' => $validatedData['fes04_folic'] ?? false,
            'folic_acid' => $validatedData['folic_acid'],
            'fhb' => $validatedData['fhb'],
            'position' => $validatedData['position'],
            'presentation' => $validatedData['presentation'],
            'fundal_height' => $validatedData['fundal_height'],
        ];
        

        // Save CheckBox1 data to CheckBox1 table
        $checkBox1 = CheckBox1::create($checkBox1Data);

        // Redirect back with a success message
        return back()->with('Success', 'Data saved successfully!');
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
