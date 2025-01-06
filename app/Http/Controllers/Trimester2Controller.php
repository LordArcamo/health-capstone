<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GeneralTrimester;
use App\Models\CheckBox2;

class Trimester2Controller extends Controller
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
            'prenatalId' => 'required|exists:prenatal,prenatalId',
            'id' => 'nullable|exists:users,id',
            'date_of_visit' => 'required|date',
            'weight' => 'required|numeric',
            'bp' => 'required|string|max:20',
            'heart_rate' => 'required|numeric',
            'aog_months' => 'nullable|integer',
            'aog_days' => 'nullable|integer',
            'trimester' => 'nullable|string|max:50',

            // Checkbox2 data validation
            'prenatal_record' => 'boolean',
            'reminded_importance' => 'boolean',
            'health_teachings' => 'boolean',
            'reminded_dangers' => 'boolean',
            'healthy_diet' => 'boolean',
            'breast_feeding' => 'boolean',
            'compliane_routine' => 'boolean',
            'referred_utz' => 'boolean',
            'fes04_folic' => 'boolean',
            'folic_acid' => 'nullable|integer',
        ]);

        $userId = auth()->id() ?? $validatedData['id'];

        if (!$userId) {
            return back()->withErrors(['error' => 'User ID is required.']);
        }

        // Split general trimester data
        $generalTrimesterData = [
            'prenatalId' => $validatedData['prenatalId'],
            'id' => $userId,
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

        // Prepare checkbox2 data
        $checkbox2Data = [
            'generalTrimesterID' => $generalTrimester->generalTrimesterID,
            'prenatal_record' => $validatedData['prenatal_record'] ?? false, // Default to false
            'reminded_importance' => $validatedData['reminded_importance'] ?? false,
            'health_teachings' => $validatedData['health_teachings'] ?? false,
            'reminded_dangers' => $validatedData['reminded_dangers'] ?? false,
            'healthy_diet' => $validatedData['healthy_diet'] ?? false,
            'breast_feeding' => $validatedData['breast_feeding'] ?? false,
            'compliane_routine' => $validatedData['compliane_routine'] ?? false,
            'referred_utz' => $validatedData['referred_utz'] ?? false,
            'fes04_folic' => $validatedData['fes04_folic'] ?? false,
            'folic_acid' => $validatedData['folic_acid']  ?? false,
        ];

        // Save checkbox2 data to database
        CheckBox2::create($checkbox2Data);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Form data saved successfully!');
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
