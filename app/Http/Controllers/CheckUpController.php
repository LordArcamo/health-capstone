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
        $checkUps = CheckUp::all(); // Fetch data from the database
        return Inertia::render('Patients', [
            'checkUps' => $checkUps, // Pass patients data to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Checkup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'required|string|max:255',
            'suffix' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'age' => 'required|numeric',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'consultationDate' => 'required|date',
            'consultationTime' => 'required|date_format:H:i',
            'modeOfTransaction' => 'required|string|max:255',
            'bloodPressure' => 'required|string|max:255',
            'temperature'  => 'required|numeric',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'providerName' => 'required|string|max:255',
            'natureOfVisit' => 'required|string|max:255',
            'visitType' => 'required|string|max:255',
            'chiefComplaints' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'medication' => 'required|string|max:255',
        ]);

        CheckUp::create($validateData);

        return back()->with('Success');
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
