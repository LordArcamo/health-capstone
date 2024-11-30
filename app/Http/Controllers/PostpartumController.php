<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Postpartum;

class PostpartumController extends Controller
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
        return Inertia::render('CheckUp/PostPartumCheckup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'lastName' => 'required|string|max:100',
            'firstName' => 'required|string|max:100',
            'middleName' => 'required|string|max:100',
            'sex' => 'required|string|max:10',
            'birthLength' => 'required|numeric|between:0,300',
            'birthWeight' => 'required|numeric|between:0,300',
            'prenatalDelivered' => 'required|string|max:100',
            'placeDelivered' => 'required|string|max:100',
            'modeOfDelivery' => 'required|string|max:100',
            'attendantAtBirth' => 'required|string|max:100',
            'deliveryDate' => 'required|date',
            'deliveryTime' => 'required|date_format:H:i',
            'dangerSignsMother' => 'required|string|max:100',
            'dangerSignsBaby' => 'required|string|max:100',
        ]);

        // Create and save the new record
        $postpartum = Postpartum::create($validatedData);

        // Return a simple success message
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
