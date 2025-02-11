<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PersonalInformation;
use App\Models\ConsultationDetails;
use App\Models\NationalImmunizationProgram;
use App\Models\PrenatalConsultationDetails;
use App\Models\VaccinationRecord;

class DoctorDashboardController extends Controller
{
    /**
     * Display the main doctor dashboard with static data.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $today = now()->toDateString();

        // Auto-cancel consultations that were not completed
        if (now()->format('H:i') === '00:00') {
            DB::table('consultation_details')
                ->whereDate('consultationDate', '<', $today)
                ->where(function ($query) {
                    $query->where('status', 'in queue')
                        ->orWhereNull('status');
                })
                ->update(['status' => 'cancelled', 'updated_at' => now()]);
    
            DB::table('prenatal_consultation_details')
                ->whereDate('consultationDate', '<', $today)
                ->where(function ($query) {
                    $query->where('status', 'in queue')
                        ->orWhereNull('status');
                })
                ->update(['status' => 'cancelled', 'updated_at' => now()]);
        }

        // Fetch patients in queue (not completed)
        $generalConsultations = DB::table('personal_information')
            ->join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
            ->whereDate('consultation_details.consultationDate', $today)
            ->where(function($query) {
                $query->where('consultation_details.status', 'in queue')
                      ->orWhereNull('consultation_details.status');
            })
            ->select(
                'consultation_details.consultationDetailsID',
                DB::raw('NULL as prenatalConsultationDetailsID'),
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
                'consultation_details.consultationTime',
                'consultation_details.modeOfTransaction',
                'consultation_details.bloodPressure',
                'consultation_details.temperature',
                'consultation_details.height',
                'consultation_details.weight',
                'consultation_details.visitType',
                DB::raw('NULL as providerName'),
                DB::raw('NULL as nameOfSpouse'),
                DB::raw('NULL as emergencyContact'),
                DB::raw('NULL as fourMember'),
                DB::raw('NULL as philhealthStatus'),
                DB::raw('NULL as philhealthNo'),
                DB::raw("'General Checkup' as checkupType"),
                DB::raw("'General' as consultationType")
            );

        // Fetch prenatal consultations in queue
        $prenatalConsultations = DB::table('personal_information')
            ->join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
            ->whereDate('prenatal_consultation_details.consultationDate', $today)
            ->where(function($query) {
                $query->where('prenatal_consultation_details.status', 'in queue')
                      ->orWhereNull('prenatal_consultation_details.status');
            })
            ->select(
                DB::raw('NULL as consultationDetailsID'),
                'prenatal_consultation_details.prenatalConsultationDetailsID',
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
                'prenatal_consultation_details.consultationDate',
                'prenatal_consultation_details.consultationTime',
                'prenatal_consultation_details.modeOfTransaction',
                'prenatal_consultation_details.bloodPressure',
                'prenatal_consultation_details.temperature',
                'prenatal_consultation_details.height',
                'prenatal_consultation_details.weight',
                DB::raw("'Prenatal' as visitType"),
                'prenatal_consultation_details.providerName',
                'prenatal_consultation_details.nameOfSpouse',
                'prenatal_consultation_details.emergencyContact',
                'prenatal_consultation_details.fourMember',
                'prenatal_consultation_details.philhealthStatus',
                'prenatal_consultation_details.philhealthNo',
                DB::raw("'Prenatal Checkup' as checkupType"),
                DB::raw("'Prenatal' as consultationType")
            );

        // Get patients in queue
        $ITRConsultation = $generalConsultations->union($prenatalConsultations)->get();

        // Generate notifications for new patients in queue
        $notifications = [];
        foreach ($ITRConsultation as $patient) {
            $notifications[] = [
                'id' => uniqid(),
                'type' => 'new_patient',
                'message' => "New {$patient->visitType} consultation: {$patient->firstName} {$patient->lastName}",
                'time' => $patient->consultationTime,
                'isRead' => false,
                'data' => [
                    'patientId' => $patient->personalId,
                    'consultationType' => $patient->visitType,
                    'consultationId' => $patient->consultationDetailsID ?? $patient->prenatalConsultationDetailsID
                ]
            ];
        }

        // Sort notifications by time
        usort($notifications, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        // Get latest completed general patients
        $latestGeneralPatients = DB::table('personal_information')
            ->join('consultation_details', 'personal_information.personalId', '=', 'consultation_details.personalId')
            ->leftJoin('visit_information', 'consultation_details.consultationDetailsID', '=', 'visit_information.consultationDetailsID')
            ->where(function ($query) use ($today) {
                $query->whereIn('consultation_details.status', ['completed', 'cancelled'])
                      ->whereDate('consultation_details.consultationDate', $today);
            })
            ->select(
                'consultation_details.consultationDetailsID',
                DB::raw('NULL as prenatalConsultationDetailsID'), // Add NULL for prenatal-specific ID
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'General' as visitType"), // Set "General" as visit type
                'consultation_details.modeOfTransaction',
                'consultation_details.consultationDate',
                'consultation_details.consultationTime',
                'consultation_details.bloodPressure',
                'consultation_details.temperature',
                'consultation_details.height',
                'consultation_details.weight',
                'consultation_details.referredFrom',
                'consultation_details.referredTo',
                'consultation_details.reasonsForReferral',
                'consultation_details.referredBy',
                'consultation_details.natureOfVisit',
                'consultation_details.visitType as specificVisitType', // Preserve specific visit type
                'consultation_details.providerName',
                'visit_information.chiefComplaints',
                'visit_information.diagnosis',
                DB::raw('NULL as nameOfSpouse'), // Add NULL for prenatal-specific fields
                DB::raw('NULL as emergencyContact'),
                DB::raw('NULL as fourMember'),
                DB::raw('NULL as philhealthStatus'),
                DB::raw('NULL as philhealthNo'),
                DB::raw('NULL as menarche'),
                DB::raw('NULL as sexualOnset'),
                DB::raw('NULL as periodDuration'),
                DB::raw('NULL as birthControl'),
                DB::raw('NULL as intervalCycle'),
                DB::raw('NULL as menopause'),
                DB::raw('NULL as lmp'),
                DB::raw('NULL as edc'),
                DB::raw('NULL as gravidity'),
                DB::raw('NULL as parity'),
                DB::raw('NULL as term'),
                DB::raw('NULL as preterm'),
                DB::raw('NULL as abortion'),
                DB::raw('NULL as living'),
                DB::raw('NULL as syphilisResult'),
                DB::raw('NULL as penicillin'),
                DB::raw('NULL as hemoglobin'),
                DB::raw('NULL as hematocrit'),
                DB::raw('NULL as urinalysis'),
                DB::raw('NULL as ttStatus'),
                DB::raw('NULL as tdDate'),
                'consultation_details.completed_at',
                'consultation_details.updated_at'
            )
            ->orderBy('consultation_details.completed_at', 'desc')
            ->orderBy('consultation_details.updated_at', 'desc');

        // Get latest completed prenatal patients
        $latestPrenatalPatients = DB::table('personal_information')
            ->join('prenatal_consultation_details', 'personal_information.personalId', '=', 'prenatal_consultation_details.personalId')
            ->leftJoin('prenatal_visit_information', 'prenatal_consultation_details.prenatalConsultationDetailsID', '=', 'prenatal_visit_information.prenatalConsultationDetailsID')
            ->where(function ($query) use ($today) {
                $query->whereIn('prenatal_consultation_details.status', ['completed', 'cancelled'])
                      ->whereDate('prenatal_consultation_details.consultationDate', $today);
            })
            ->select(
                DB::raw('NULL as consultationDetailsID'), // Add NULL for general-specific ID
                'prenatal_consultation_details.prenatalConsultationDetailsID',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.middleName',
                'personal_information.suffix',
                'personal_information.age',
                'personal_information.birthdate',
                'personal_information.purok',
                'personal_information.barangay',
                'personal_information.contact',
                'personal_information.sex',
                DB::raw("'Prenatal' as visitType"), // Set "Prenatal" as visit type
                'prenatal_consultation_details.modeOfTransaction',
                'prenatal_consultation_details.consultationDate',
                'prenatal_consultation_details.consultationTime',
                'prenatal_consultation_details.bloodPressure',
                'prenatal_consultation_details.temperature',
                'prenatal_consultation_details.height',
                'prenatal_consultation_details.weight',
                DB::raw('NULL as referredFrom'), // Add NULL for general-specific fields
                DB::raw('NULL as referredTo'),
                DB::raw('NULL as reasonsForReferral'),
                DB::raw('NULL as referredBy'),
                DB::raw('NULL as natureOfVisit'),
                DB::raw('NULL as specificVisitType'),
                'prenatal_consultation_details.providerName',
                DB::raw('NULL as chiefComplaints'), // Add NULL for general-specific fields
                DB::raw('NULL as diagnosis'),
                'prenatal_consultation_details.nameOfSpouse',
                'prenatal_consultation_details.emergencyContact',
                'prenatal_consultation_details.fourMember',
                'prenatal_consultation_details.philhealthStatus',
                'prenatal_consultation_details.philhealthNo',
                'prenatal_visit_information.menarche',
                'prenatal_visit_information.sexualOnset',
                'prenatal_visit_information.periodDuration',
                'prenatal_visit_information.birthControl',
                'prenatal_visit_information.intervalCycle',
                'prenatal_visit_information.menopause',
                'prenatal_visit_information.lmp',
                'prenatal_visit_information.edc',
                'prenatal_visit_information.gravidity',
                'prenatal_visit_information.parity',
                'prenatal_visit_information.term',
                'prenatal_visit_information.preterm',
                'prenatal_visit_information.abortion',
                'prenatal_visit_information.living',
                'prenatal_visit_information.syphilisResult',
                'prenatal_visit_information.penicillin',
                'prenatal_visit_information.hemoglobin',
                'prenatal_visit_information.hematocrit',
                'prenatal_visit_information.urinalysis',
                'prenatal_visit_information.ttStatus',
                'prenatal_visit_information.tdDate',
                'prenatal_consultation_details.completed_at',
                'prenatal_consultation_details.updated_at'
            )
            ->orderBy('prenatal_consultation_details.completed_at', 'desc')
            ->orderBy('prenatal_consultation_details.updated_at', 'desc');

        // Combine and get latest 5 patients
        $latestPatients = $latestGeneralPatients->unionAll($latestPrenatalPatients) // Use UNION ALL to avoid column conflicts
            ->orderBy('completed_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();




        // Get total patients count
        $totalPatients = DB::table('consultation_details')->select('consultationDate as date')
        ->unionAll(
            DB::table('prenatal_consultation_details')->select('consultationDate as date')
        )
        ->unionAll(
            DB::table('national_immunization_programs')->select('created_at as date')
        )
        ->unionAll(
            DB::table('vaccination_records')->select('dateOfVisit as date')
        )
        ->count();

        // Get today's appointments count
        $todaysConsultation = ConsultationDetails::whereDate('consultationDate', $today)->count() +
                      PrenatalConsultationDetails::whereDate('consultationDate', $today)->count();


        // Get critical cases count (you may need to adjust this based on your criteria)
        $criticalCases = 0;

                // Retrieve and map dates from ConsultationDetails
                $consultationDates = ConsultationDetails::select('consultationDate')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->consultationDate,
                        'type' => 'Consultation Details',
                    ];
                });

                // Retrieve and map dates from PrenatalConsultationDetails
                $prenatalConsultationDates = PrenatalConsultationDetails::select('consultationDate')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'date' => $item->consultationDate,
                            'type' => 'Prenatal Consultation',
                        ];
                    });

                // Retrieve and map dates from NationalImmunizationProgram
                $immunizationDates = NationalImmunizationProgram::select('created_at')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'date' => $item->created_at,
                            'type' => 'Immunization Program',
                        ];
                    });

                // Retrieve and map dates from VaccinationRecord
                $vaccinationDates = VaccinationRecord::select('dateOfVisit')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'date' => $item->dateOfVisit,
                            'type' => 'Vaccination Record',
                        ];
                    });

                // Merge all dates into a single collection
                $allDates = $consultationDates
                    ->merge($prenatalConsultationDates)
                    ->merge($immunizationDates)
                    ->merge($vaccinationDates);


        return Inertia::render('Doctor/DoctorDashboard', [
            'allDates' => $allDates,
            'totalPatients' => $totalPatients,
            'ITRConsultation' => $ITRConsultation,
            'latestPatients' => $latestPatients,
            'todaysConsultation' => $todaysConsultation,
            'criticalCases' => $criticalCases,
            'notifications' => $notifications,
        ]);
    }

    /**
     * Display a tailored doctor dashboard with user data.
     *
     * @return \Inertia\Response
     */
    public function doctor()
    {
        return Inertia::render('Doctor/DoctorDashboard', [
            'pageTitle' => 'Doctor Dashboard',
            'user' => auth()->user(),
        ]);
    }

    public function checkup()
    {
        // Replace with actual database query to fetch the next patient in the queue
        $patientsQueue = [
            // Example: No patients in the queue
            // ['id' => 1, 'name' => 'John Doe', 'age' => 34, 'reason' => 'Fever'],
        ];

        $nextPatient = $patientsQueue[0] ?? null;

        if (!$nextPatient) {
            return Inertia::render('Doctor/DoctorCheckup', [
                'patient' => null,
                'message' => 'No patients are currently in the checkup queue.',
            ]);
        }

        return Inertia::render('Doctor/DoctorCheckup', [
            'patient' => $nextPatient,
        ]);
    }

    public function markAsCancelled(Request $request)
    {
        try {
            DB::beginTransaction();

            if ($request->consultationDetailsID) {
                // Regular consultation
                $consultation = ConsultationDetails::findOrFail($request->consultationDetailsID);
                $consultation->status = 'cancelled';
                $consultation->save();

                // Create or update visit_information with 'None' values
                $visitInfo = DB::table('visit_information')
                    ->where('consultationDetailsID', $request->consultationDetailsID)
                    ->first();

                if ($visitInfo) {
                    // Update existing record
                    DB::table('visit_information')
                        ->where('consultationDetailsID', $request->consultationDetailsID)
                        ->update([
                            'chiefComplaints' => 'None',
                            'diagnosis' => 'None',

                            'updated_at' => now()
                        ]);
                } else {
                    // Create new record
                    DB::table('visit_information')->insert([
                        'consultationDetailsID' => $request->consultationDetailsID,
                        'id' => auth()->id(),
                        'chiefComplaints' => 'None',
                        'diagnosis' => 'None',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } elseif ($request->prenatalConsultationDetailsID) {
                // Prenatal consultation
                $prenatalConsultation = PrenatalConsultationDetails::findOrFail($request->prenatalConsultationDetailsID);
                $prenatalConsultation->status = 'cancelled';
                $prenatalConsultation->save();

                // Create or update prenatal_visit_information with 'None' values
                $visitInfo = DB::table('prenatal_visit_information')
                    ->where('prenatalConsultationDetailsID', $request->prenatalConsultationDetailsID)
                    ->first();

                if ($visitInfo) {
                    // Update existing record
                    DB::table('prenatal_visit_information')
                        ->where('prenatalConsultationDetailsID', $request->prenatalConsultationDetailsID)
                        ->update([
                            'menarche' => 'None',
                            'sexualOnset' => 'None',
                            'periodDuration' => 'None',
                            'birthControl' => 'None',
                            'intervalCycle' => 'None',
                            'menopause' => 'None',
                            'lmp' => now(),
                            'edc' => now(),
                            'gravidity' => 'None',
                            'parity' => 'None',
                            'term' => 'None',
                            'preterm' => 'None',
                            'abortion' => 'None',
                            'living' => 'None',
                            'syphilisResult' => 'None',
                            'penicillin' => 'None',
                            'hemoglobin' => 0.00,
                            'hematocrit' => 0.00,
                            'urinalysis' => 'None',
                            'ttStatus' => 'None',
                            'tdDate' => now(),
                            'updated_at' => now()
                        ]);
                } else {
                    // Create new record
                    DB::table('prenatal_visit_information')->insert([
                        'prenatalConsultationDetailsID' => $request->prenatalConsultationDetailsID,
                        'id' => auth()->id(),
                        'menarche' => 'None',
                        'sexualOnset' => 'None',
                        'periodDuration' => 'None',
                        'birthControl' => 'None',
                        'intervalCycle' => 'None',
                        'menopause' => 'None',
                        'lmp' => now(),
                        'edc' => now(),
                        'gravidity' => 'None',
                        'parity' => 'None',
                        'term' => 'None',
                        'preterm' => 'None',
                        'abortion' => 'None',
                        'living' => 'None',
                        'syphilisResult' => 'None',
                        'penicillin' => 'None',
                        'hemoglobin' => 0.00,
                        'hematocrit' => 0.00,
                        'urinalysis' => 'None',
                        'ttStatus' => 'None',
                        'tdDate' => now(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            } else {
                throw new \Exception('No consultation ID provided');
            }

            DB::commit();
            return redirect()->back()->with('success', 'Consultation marked as cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to cancel consultation. Please try again. Error: ' . $e->getMessage());
        }
    }
}
