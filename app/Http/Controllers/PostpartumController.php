<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Postpartum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $prenatalConsultationDetailsID = request('prenatalConsultationDetailsID');
        $postpartum = null;

        if ($prenatalConsultationDetailsID) {
            $postpartum = Postpartum::where('prenatalConsultationDetailsID', $prenatalConsultationDetailsID)->first();
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
        try {
            // Add the authenticated user's ID to the request
            $requestData = $request->all();
            $requestData['id'] = auth()->id();

            $validatedData = validator($requestData, [
                'prenatalConsultationDetailsID' => 'nullable|exists:prenatal_consultation_details,prenatalConsultationDetailsID',
                'id' => 'required|exists:users,id',
                'lastName' => 'required|string|max:100',
                'firstName' => 'required|string|max:100',
                'middleName' => 'required|string|max:100',
                'sex' => 'required|string|max:10',
                'prenatalDelivered' => 'required|string|max:100',
                'placeDelivered' => 'required|string|max:100',
                'modeOfDelivery' => 'required|string|max:100',
                'birthLength' => 'required|numeric|between:0,500',
                'birthWeight' => 'required|numeric|between:0,100',
                'deliveryDate' => 'required|date',
                'deliveryTime' => 'required|date_format:H:i',
                'attendantBirth' => 'required|string|max:100',
                'dateInitiatedBreastfeeding' => 'required|date',
                'timeInitiatedBreastfeeding' => 'required|date_format:H:i',
                'dateOfPostpartumVisitTwentyFourHoursDelivery' => 'required|date',
                'dateOfPostpartumVisitOneWeekDelivery' => 'required|date',
                'dateVitaminA' => 'required|date',
                'dateIronGiven' => 'required|date',
                'noIronGiven' => 'required|numeric|between:0,100',
                'dangerSignsMother' => 'required|string|max:100',
                'dangerSignsBaby' => 'required|string|max:100',
            ])->validate();

            DB::beginTransaction();

            // Log incoming data for debugging
            Log::info('Creating postpartum record with data:', array_merge(
                ['user_id' => auth()->id()],
                collect($validatedData)->except(['password'])->toArray()
            ));

            $postpartum = Postpartum::create($validatedData);

            DB::commit();

            Log::info('Successfully created postpartum record', [
                'postpartum_id' => $postpartum->postpartumId,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Postpartum record created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed for postpartum data', [
                'errors' => $e->errors(),
                'user_id' => auth()->id()
            ]);
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to create postpartum record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->withErrors([
                'error' => 'Failed to save postpartum data. ' . $e->getMessage()
            ]);
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
     * Get postpartum data by prenatalConsultationDetailsID.
     */
    public function getByprenatalConsultationDetailsID($prenatalConsultationDetailsID)
    {
        try {
            // Fetch the latest data for this prenatalId
            $postpartum = Postpartum::where('prenatalConsultationDetailsID', $prenatalConsultationDetailsID)
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
