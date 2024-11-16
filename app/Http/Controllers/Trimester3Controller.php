<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GeneralTrimester;
use App\Models\CheckBox3;

class Trimester3Controller extends Controller
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
            'aog_months' => 'nullable|integer',
            'aog_days' => 'nullable|integer',
            'trimester' => 'nullable|string|max:50',

            // Checkbox3 fields validation
            'prenatal_checkup' => 'boolean',
            'pe_done' => 'boolean',
            'prenatal_record' => 'boolean',
            'reminded_importance' => 'boolean',
            'health_teachings' => 'boolean',
            'reminded_dangers' => 'boolean',
            'healthy_diet' => 'boolean',
            'breast_feeding' => 'boolean',
            'compliance_routine' => 'boolean',
            'referred_utz' => 'boolean',
            'information_newborn' => 'boolean',
            'fes04_folic' => 'boolean',
            'folic_acid' => 'nullable|integer',
            'information_family' => 'nullable|boolean',
            'fhb' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:50',
            'presentation' => 'nullable|string|max:50',
            'fundal_height' => 'nullable|string|max:50',
        ]);

        // Split general trimester data
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

        // Save GeneralTrimester data
        $generalTrimester = GeneralTrimester::create($generalTrimesterData);

        // Prepare Checkbox3 data
        $checkbox3Data = [
            'generalTrimesterID' => $generalTrimester->generalTrimesterID,
            'prenatal_checkup' => $validatedData['prenatal_checkup'] ?? false,
            'pe_done' => $validatedData['pe_done'] ?? false,
            'prenatal_record' => $validatedData['prenatal_record'] ?? false,
            'reminded_importance' => $validatedData['reminded_importance'] ?? false,
            'health_teachings' => $validatedData['health_teachings'] ?? false,
            'reminded_dangers' => $validatedData['reminded_dangers'] ?? false,
            'healthy_diet' => $validatedData['healthy_diet'] ?? false,
            'breast_feeding' => $validatedData['breast_feeding'] ?? false,
            'compliance_routine' => $validatedData['compliance_routine'] ?? false,
            'referred_utz' => $validatedData['referred_utz'] ?? false,
            'information_newborn' => $validatedData['information_newborn'] ?? false,
            'fes04_folic' => $validatedData['fes04_folic'] ?? false,
            'folic_acid' => $validatedData['folic_acid'],
            'information_family' => $validatedData['information_family'] ?? null,
            'fhb' => $validatedData['fhb'],
            'position' => $validatedData['position'],
            'presentation' => $validatedData['presentation'],
            'fundal_height' => $validatedData['fundal_height'],
        ];

        // Save Checkbox3 data to database
        CheckBox3::create($checkbox3Data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Trimester 3 data saved successfully!');
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
