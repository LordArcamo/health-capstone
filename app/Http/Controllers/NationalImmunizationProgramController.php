<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\NationalImmunizationProgram;
use App\Models\PersonalInformation;

class NationalImmunizationProgramController extends Controller
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
        return Inertia::render('NationalImmunization');
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
            'birthplace' => 'required|string|max:255',
            'bloodtype' => 'required|string|max:255',
            'mothername' => 'required|string|max:255',
            'dswdNhts' => 'required|string|max:255',
            'facilityHouseholdno'  => 'required|string|max:255',
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
        

        $national_immunization_programs = new NationalImmunizationProgram();
        $national_immunization_programs->birthplace = $validatedData['birthplace'];
        $national_immunization_programs->bloodtype = $validatedData['bloodtype'];
        $national_immunization_programs->mothername = $validatedData['mothername'];
        $national_immunization_programs->dswdNhts = $validatedData['dswdNhts'];
        $national_immunization_programs->facilityHouseholdno = $validatedData['facilityHouseholdno'];
        $national_immunization_programs->houseHoldno = $validatedData['houseHoldno'];
        $national_immunization_programs->fourpsmember = $validatedData['fourpsmember'];
        $national_immunization_programs->PCBMember = $validatedData['PCBMember'];
        $national_immunization_programs->philhealthMember = $validatedData['philhealthMember'];
        $national_immunization_programs->statusType = $validatedData['statusType'];
        $national_immunization_programs->philhealthNo = $validatedData['philhealthNo'];
        $national_immunization_programs->ifMember = $validatedData['ifMember'];
        $national_immunization_programs->familyMember = $validatedData['familyMember'];
        $national_immunization_programs->ttstatus = $validatedData['ttstatus'];
        $national_immunization_programs->dateAssesed = $validatedData['dateAssesed'];
        $national_immunization_programs->date = $validatedData['date'];
        $national_immunization_programs->place = $validatedData['place'];
        $national_immunization_programs->guardian = $validatedData['guardian'];
        $national_immunization_programs->save();


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