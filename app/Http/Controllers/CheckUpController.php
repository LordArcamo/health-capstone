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
        $personalInformation = PersonalInformation::all(); // Fetch data from the database
        $checkUps = CheckUp::all();
        return Inertia::render('Patients', [
            'personalInformation' => $personalInformation,
            'checkUps' => $checkUps, // Pass patients data to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('IndividualTreatmentRecord');
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
        ]);

        $general_information = new PersonalInformation();
        $general_information->firstName = $validatedData['firstName'];
        $general_information->lastName = $validatedData['lastName'];
        $general_information->middleName = $validatedData['middleName'];
        $general_information->suffix = $validatedData['suffix'];
        $general_information->address = $validatedData['address'];
        $general_information->age = $validatedData['age'];
        $general_information->birthdate = $validatedData['birthdate'];
        $general_information->contact = $validatedData['contact'];
        $general_information->sex = $validatedData['sex'];
        $general_information->save();


        $validatedData = $request->validate([
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
        

        $check_ups = new CheckUp();
        $check_ups->consultationDate = $validatedData['consultationDate'];
        $check_ups->consultationTime = $validatedData['consultationTime'];
        $check_ups->modeOfTransaction = $validatedData['modeOfTransaction'];
        $check_ups->bloodPressure = $validatedData['bloodPressure'];
        $check_ups->temperature = $validatedData['temperature'];
        $check_ups->height = $validatedData['height'];
        $check_ups->weight = $validatedData['weight'];
        $check_ups->providerName = $validatedData['providerName'];
        $check_ups->natureOfVisit = $validatedData['natureOfVisit'];
        $check_ups->visitType = $validatedData['visitType'];
        $check_ups->chiefComplaints = $validatedData['chiefComplaints'];
        $check_ups->diagnosis = $validatedData['diagnosis'];
        $check_ups->medication = $validatedData['medication'];
        $check_ups->save();


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
