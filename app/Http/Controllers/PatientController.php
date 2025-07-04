<?php
namespace App\Http\Controllers;

use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CheckUp;
use App\Models\ConsultationDetails;
use App\Models\VisitInformation;
use Illuminate\Support\Facades\DB;



class PatientController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');
        \Log::debug("Search query: " . $query); // Log query to check if it's coming through

        $patients = PersonalInformation::where('firstName', 'like', "%$query%")
            ->orWhere('lastName', 'like', "%$query%")
            ->orWhere('personalId', 'like', "%$query%")
            ->get();

        return Inertia::render('Checkup', [
            'patients' => $patients ?? [],
        ]);
    }


    public function disease(Request $request)
    {
        $diagnosis = $request->input('diagnosis', ''); // Retrieve the diagnosis filter from the request, default is empty

        // Use LEFT JOIN to fetch cases filtered by diagnosis if provided
        $cases = PersonalInformation::join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
        ->join('visit_information', 'consultation_details.consultationDetailsID', '=', 'visit_information.consultationDetailsID')
        ->select(
            'personal_information.personalId',
            'personal_information.created_at',
            'personal_information.firstName',
            'personal_information.lastName',
            'personal_information.middleName',
            'personal_information.suffix',
            'personal_information.purok',
            'personal_information.barangay',
            'personal_information.age',
            'personal_information.birthdate',
            'personal_information.contact',
            'personal_information.sex',
            'consultation_details.consultationDate',
            'consultation_details.consultationTime',
            'consultation_details.modeOfTransaction',
            'consultation_details.bloodPressure',
            'consultation_details.temperature',
            'consultation_details.height',
            'consultation_details.weight',
            'consultation_details.providerName',
            'consultation_details.natureOfVisit',
            'consultation_details.visitType',
            'visit_information.diagnosis',
            'visit_information.chiefComplaints',
            'visit_information.requireLabTest',
            'visit_information.selectedLabTests'
        )
        ->when(!empty($diagnosis), function ($query) use ($diagnosis) {
            return $query->where('visit_information.diagnosis', 'LIKE', "%$diagnosis%");
        })
        ->distinct()
        ->get();
    

        // Return the filtered cases to the frontend
        return Inertia::render('Table/DiseaseCases', [
            'cases' => $cases, // Pass the filtered cases
            'diagnosisFilter' => $diagnosis, // Current diagnosis filter for UI
        ]);
    }




    public function show()
    {
        // Combine patients from all four tables with their personal information
        $patients = PersonalInformation::select(
            'personal_information.personalId',
            'personal_information.created_at',
            'personal_information.firstName',
            'personal_information.lastName',
            'personal_information.middleName',
            'personal_information.suffix',
            'personal_information.purok',
            'personal_information.barangay',
            'personal_information.age',
            'personal_information.birthdate',
            'personal_information.contact',
            'personal_information.sex',
            'consultation_details.visitType',
            DB::raw("'consultation_details' as source"),  // Tag the source table
            DB::raw("consultation_details.consultationDate as consultationDate") // Unified date column
        )
        ->join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
    
        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Prenatal' as visitType"),
                DB::raw("'prenatal_consultation_details' as source"),
                DB::raw("prenatal_consultation_details.consultationDate as consultationDate") // Unified date column
            )
            ->join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
        )
    
        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Immunization' as visitType"),
                DB::raw("'national_immunization_programs' as source"),
                DB::raw("national_immunization_programs.dateAssesed as consultationDate") // Unified date column
            )
            ->join('national_immunization_programs', 'personal_information.personalId', '=', 'national_immunization_programs.personalId')
        )
    
        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Vaccination' as visitType"),
                DB::raw("'vaccination_records' as source"),
                DB::raw("vaccination_records.dateOfVisit as consultationDate") // Unified date column
            )
            ->join('vaccination_records', 'personal_information.personalId', '=', 'vaccination_records.personalId')
        )
    
        ->distinct()  // Remove duplicate entries
        ->get();

        // Pass data to the frontend
        return Inertia::render('Table/Patient', [
            'totalPatients' => $patients,
        ]);
    }

    public function showQueue()
    {
        // Combine patients from all four tables with their personal information
        $patients = PersonalInformation::select(
            'personal_information.personalId',
            'personal_information.created_at',
            'personal_information.firstName',
            'personal_information.lastName',
            'personal_information.middleName',
            'personal_information.suffix',
            'personal_information.purok',
            'personal_information.barangay',
            'personal_information.age',
            'personal_information.birthdate',
            'personal_information.contact',
            'personal_information.sex',
            'consultation_details.visitType',
            DB::raw("'consultation_details' as source"),  // Tag the source table
            DB::raw("consultation_details.consultationDate as consultationDate") // Unified date column
        )
        ->join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')

        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Prenatal' as visitType"),
                DB::raw("'prenatal_consultation_details' as source"),
                DB::raw("prenatal_consultation_details.consultationDate as consultationDate") // Unified date column
            )
            ->join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
        )

        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Immunization' as visitType"),
                DB::raw("'national_immunization_programs' as source"),
                DB::raw("national_immunization_programs.dateAssesed as consultationDate") // Unified date column
            )
            ->join('national_immunization_programs', 'personal_information.personalId', '=', 'national_immunization_programs.personalId')
        )

        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Vaccination' as visitType"),
                DB::raw("'vaccination_records' as source"),
                DB::raw("vaccination_records.dateOfVisit as consultationDate") // Unified date column
            )
            ->join('vaccination_records', 'personal_information.personalId', '=', 'vaccination_records.personalId')
        )

        ->distinct()  // Remove duplicate entries
        ->get();

        // Pass data to the frontend
        return Inertia::render('Table/PatientQueu', [
            'totalPatients' => $patients,
        ]);
    }

    public function showCritical()
    {
        // Combine patients from all four tables with their personal information
        $patients = PersonalInformation::select(
            'personal_information.personalId',
            'personal_information.created_at',
            'personal_information.firstName',
            'personal_information.lastName',
            'personal_information.middleName',
            'personal_information.suffix',
            'personal_information.purok',
            'personal_information.barangay',
            'personal_information.age',
            'personal_information.birthdate',
            'personal_information.contact',
            'personal_information.sex',
            'consultation_details.visitType',
            DB::raw("'consultation_details' as source"),  // Tag the source table
            DB::raw("consultation_details.consultationDate as consultationDate") // Unified date column
        )
        ->join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')

        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Prenatal' as visitType"),
                DB::raw("'prenatal_consultation_details' as source"),
                DB::raw("prenatal_consultation_details.consultationDate as consultationDate") // Unified date column
            )
            ->join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
        )

        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Immunization' as visitType"),
                DB::raw("'national_immunization_programs' as source"),
                DB::raw("national_immunization_programs.dateAssesed as consultationDate") // Unified date column
            )
            ->join('national_immunization_programs', 'personal_information.personalId', '=', 'national_immunization_programs.personalId')
        )

        ->unionAll(
            PersonalInformation::select(
                'personal_information.personalId',
                'personal_information.created_at',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Vaccination' as visitType"),
                DB::raw("'vaccination_records' as source"),
                DB::raw("vaccination_records.dateOfVisit as consultationDate") // Unified date column
            )
            ->join('vaccination_records', 'personal_information.personalId', '=', 'vaccination_records.personalId')
        )

        ->distinct()  // Remove duplicate entries
        ->get();

        // Pass data to the frontend
        return Inertia::render('Table/TodayConsultation', [
            'totalPatients' => $patients,
        ]);
    }

    
    public function showReferred(){
        $referredPatients = PersonalInformation::join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
        ->where('consultation_details.modeOfTransaction', 'Referral')
        ->select(
            'personal_information.personalId',
            'personal_information.firstName',
            'personal_information.lastName',
            'personal_information.middleName',
            'personal_information.suffix',
            'personal_information.purok',
            'personal_information.barangay',
            'personal_information.age',
            'personal_information.birthdate',
            'personal_information.contact',
            'personal_information.sex',
            'consultation_details.consultationDate',
            'consultation_details.modeOfTransaction',
            'consultation_details.referredFrom',
            'consultation_details.referredTo',
            'consultation_details.reasonsForReferral',
            'consultation_details.referredBy',
        )
        ->get();

        return Inertia::render('Table/ReferredPatient', [
            'referredPatients' => $referredPatients,
        ]);

    }



}

