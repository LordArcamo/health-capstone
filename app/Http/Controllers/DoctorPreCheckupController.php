<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalInformation;
use App\Models\PrenatalConsultationDetails;
use App\Models\PrenatalVisitInformation;

class DoctorPreCheckupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Join ConsultationDetails, PersonalInformation, and VisitInformation
        $data = ConsultationDetails::with(['personalInformation', 'visitInformation'])->get();

        // Pass the combined data to the view
        return Inertia::render('ITRTable', [
            'ITRData' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $prenatalConsultationDetailsID = $request->input('prenatalConsultationDetailsID');

        if (!$prenatalConsultationDetailsID) {
            return Inertia::render('CheckUp/PrenatalDoctorCheckup', [
                'prenatalConsultationDetailsID' => null
            ]);
        }

        $prenatalConsultationDetail = DB::table('prenatal_consultation_details')
            ->join('personal_information', 'prenatal_consultation_details.personalId', '=', 'personal_information.personalId')
            ->where('prenatal_consultation_details.prenatalConsultationDetailsID', $prenatalConsultationDetailsID)
            ->select(
                'prenatal_consultation_details.*',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.purok',
                'personal_information.barangay'
            )
            ->first();

            if (!$prenatalConsultationDetail) {
                return Inertia::render('CheckUp/PrenatalDoctorCheckup', [
                    'prenatalConsultationDetailsID' => null
                ]);
            }

            return Inertia::render('CheckUp/PrenatalDoctorCheckup', [
                'prenatalConsultationDetails' => $prenatalConsultationDetail
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $prenatalVisitInfo = new PrenatalVisitInformation();
            $prenatalVisitInfo->prenatalConsultationDetailsID = $request->input('prenatalConsultationDetailsID');
            $prenatalVisitInfo->menarche = $request->input('menarche');
            $prenatalVisitInfo->sexualOnset = $request->input('sexualOnset');
            $prenatalVisitInfo->periodDuration = $request->input('periodDuration');
            $prenatalVisitInfo->birthControl = $request->input('birthControl');
            $prenatalVisitInfo->intervalCycle = $request->input('intervalCycle');
            $prenatalVisitInfo->menopause = $request->input('menopause');
            $prenatalVisitInfo->lmp = $request->input('lmp');
            $prenatalVisitInfo->edc = $request->input('edc');
            $prenatalVisitInfo->gravidity = $request->input('gravidity');
            $prenatalVisitInfo->parity = $request->input('parity');
            $prenatalVisitInfo->term = $request->input('term');
            $prenatalVisitInfo->preterm = $request->input('preterm');
            $prenatalVisitInfo->abortion = $request->input('abortion');
            $prenatalVisitInfo->living = $request->input('living');
            $prenatalVisitInfo->syphilisResult = $request->input('syphilisResult');
            $prenatalVisitInfo->penicillin = $request->input('penicillin');
            $prenatalVisitInfo->hemoglobin = $request->input('hemoglobin');
            $prenatalVisitInfo->hematocrit = $request->input('hematocrit');
            $prenatalVisitInfo->urinalysis = $request->input('urinalysis');
            $prenatalVisitInfo->ttStatus = $request->input('ttStatus');
            $prenatalVisitInfo->tdDate = $request->input('tdDate');
            $prenatalVisitInfo->id = auth()->id(); // Add the authenticated user's ID
            $prenatalVisitInfo->save();

                DB::table('prenatal_consultation_details')
                    ->where('prenatalConsultationDetailsID', $request->input('prenatalConsultationDetailsID'))
                    ->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                        'id' => auth()->id() // Add the authenticated user's ID
                    ]);

            DB::commit();
            return redirect()->route('doctor.dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
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
