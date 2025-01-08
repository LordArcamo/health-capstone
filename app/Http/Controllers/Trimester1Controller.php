<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GeneralTrimester;
use App\Models\CheckBox1;

class Trimester1Controller extends Controller
{

    // public function fetchTrimesterData(Request $request, $prenatalConsultationDetailsID, $trimester)
    // {
    //     // Fetch the trimester data
    //     $trimesterData = GeneralTrimester::with('checkbox1')
    //         ->where('prenatalConsultationDetailsID', $prenatalConsultationDetailsID)
    //         ->where('trimester', $trimester)
    //         ->first();

    //     // Render the TrimesterOneForm component with the fetched data
    //     return Inertia::render('Components/Trimester/TrimesterOneForm', [
    //         'prefilledData' => $trimesterData ?? [],
    //         'prenatalConsultationDetailsID' => $prenatalConsultationDetailsID,
    //         'trimester' => $trimester,
    //     ]);
    // }




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
            'prenatalConsultationDetailsID' => 'required|exists:prenatal_consultation_details,prenatalConsultationDetailsID',
            'id' => 'nullable|exists:users,id',
            'date_of_visit' => 'required|date',
            'weight' => 'required|numeric',
            'bp' => 'required|string|max:20',
            'heart_rate' => 'required|numeric',
            'aog_months' => 'required|integer',
            'aog_days' => 'required|integer',
            'trimester' => 'required|string|max:50',

            // CheckBox1 data validation
            'prenatal_checkup' => 'boolean',
            'pe_done' => 'boolean',
            'prenatal_record' => 'boolean',
            'birth_plan_done' => 'boolean',
            'nkfda' => 'boolean',
            'health_teachings' => 'boolean',
            'referred_for' => 'boolean',
            'healthy_diet' => 'boolean',
            'fes04_folic' => 'boolean',
            'folic_acid' => 'nullable|integer',
            'fhb' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:50',
            'presentation' => 'nullable|string|max:50',
            'fundal_height' => 'nullable|string|max:50',
        ]);

        $userId = auth()->id() ?? $validatedData['id'];

        if (!$userId) {
            return back()->withErrors(['error' => 'User ID is required.']);
        }

        // Split validated data for GeneralTrimester
        $generalTrimesterData = [
            'prenatalConsultationDetailsID' => $validatedData['prenatalConsultationDetailsID'],
            'id' => $userId,
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
            'folic_acid' => $validatedData['folic_acid'] ?? false,
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
