<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CheckUp;
use App\Models\PersonalInformation;


class CheckUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $checkUps = CheckUp::all();
        return Inertia::render('IndividualTreatmentRecord', [
            'checkUps' => $checkUps, // Pass patients data to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('IndividualTreatmentRecordCheckup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate all fields in a single validation step
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


        $itr = new CheckUp();

        // Assign all the validated data to the model
        $itr->firstName = $validatedData['firstName'];
        $itr->lastName = $validatedData['lastName'];
        $itr->middleName = $validatedData['middleName'];
        $itr->suffix = $validatedData['suffix'];
        $itr->address = $validatedData['address'];
        $itr->age = $validatedData['age'];
        $itr->birthdate = $validatedData['birthdate'];
        $itr->contact = $validatedData['contact'];
        $itr->sex = $validatedData['sex'];
        $itr->consultationDate = $validatedData['consultationDate'];
        $itr->consultationTime = $validatedData['consultationTime'];
        $itr->modeOfTransaction = $validatedData['modeOfTransaction'];
        $itr->bloodPressure = $validatedData['bloodPressure'];
        $itr->temperature = $validatedData['temperature'];
        $itr->height = $validatedData['height'];
        $itr->weight = $validatedData['weight'];
        $itr->providerName = $validatedData['providerName'];
        $itr->natureOfVisit = $validatedData['natureOfVisit'];
        $itr->visitType = $validatedData['visitType'];
        $itr->chiefComplaints = $validatedData['chiefComplaints'];
        $itr->diagnosis = $validatedData['diagnosis'];
        $itr->medication = $validatedData['medication'];
        $itr->save();

        // Redirect back with success message
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
