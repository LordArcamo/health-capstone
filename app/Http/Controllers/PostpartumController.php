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
        $prenatalId = request('prenatalId');
        $postpartum = null;
        
        if ($prenatalId) {
            $postpartum = Postpartum::where('prenatalId', $prenatalId)->first();
        }

        return Inertia::render('CheckUp/PostPartumCheckup', [
            'existingData' => $postpartum
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'prenatalId' => 'required|exists:prenatal,prenatalId',
            'lastName' => 'required|string|max:100',
            'firstName' => 'required|string|max:100',
            'middleName' => 'required|string|max:100',
            'sex' => 'required|in:M,F',
            'birthLength' => 'required|numeric|between:0,100',
            'birthWeight' => 'required|numeric|between:0,10',
            'deliveryDate' => 'required|date',
            'deliveryTime' => 'required|date_format:H:i',
            'dateInitiatedBreastfeeding' => 'required|date',
            'timeInitiatedBreastfeeding' => 'required|date_format:H:i',
            'dateVitaminA' => 'required|date',
            'dangerSignsMother' => 'required|string|max:100',
        ]);

        try {
            $postpartum = Postpartum::create($validatedData);
            return back()->with('Success', 'Data saved successfully!');
        } catch (\Exception $e) {
            return back()->with('Error', 'Failed to save data.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $postpartum = Postpartum::findOrFail($id);
        return response()->json($postpartum);
    }

    /**
     * Get postpartum data by prenatalId.
     */
    public function getByPrenatalId($prenatalId)
    {
        try {
            // Fetch the latest data for this prenatalId
            $postpartum = Postpartum::where('prenatalId', $prenatalId)
                ->latest('created_at')  // Order by latest created_at
                ->first();

            if ($postpartum) {
                return response()->json([
                    'success' => true,
                    'data' => $postpartum,
                    'message' => 'Latest data found'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'No existing data'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching postpartum data: ' . $e->getMessage()
            ], 500);
        }
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
    public function update(Request $request, $id)
    {
        $postpartum = Postpartum::findOrFail($id);
        
        $validatedData = $request->validate([
            'lastName' => 'sometimes|required|string|max:100',
            'firstName' => 'sometimes|required|string|max:100',
            'middleName' => 'sometimes|required|string|max:100',
            'sex' => 'sometimes|required|in:M,F',
            'birthLength' => 'sometimes|required|numeric|between:0,100',
            'birthWeight' => 'sometimes|required|numeric|between:0,10',
            'deliveryDate' => 'sometimes|required|date',
            'deliveryTime' => 'sometimes|required|date_format:H:i',
            'dateInitiatedBreastfeeding' => 'sometimes|required|date',
            'timeInitiatedBreastfeeding' => 'sometimes|required|date_format:H:i',
            'dateVitaminA' => 'sometimes|required|date',
            'dangerSignsMother' => 'sometimes|required|string|max:100',
        ]);

        try {
            $postpartum->update($validatedData);
            return response()->json([
                'message' => 'Postpartum record updated successfully',
                'data' => $postpartum
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating postpartum record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
