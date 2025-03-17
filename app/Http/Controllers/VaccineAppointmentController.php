<?php

namespace App\Http\Controllers;

use App\Models\VaccineAppointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VaccineAppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index()
    {
        $appointments = VaccineAppointment::with('vaccinationRecord')
            ->orderBy('appointmentDate', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'vacAppointmentId' => $appointment->vacAppointmentId,
                    'vaccinationId' => $appointment->vaccinationId,
                    'appointmentDate' => $appointment->appointmentDate,
                    'status' => $appointment->status,
                    'notes' => $appointment->notes
                ];
            });

        return Inertia::render('Appointments/Index', [
            'appointments' => $appointments
        ]);
    }

    /**
     * Store a new appointment.
     */
    public function store(Request $request)
    {
        // Log incoming request
        \Log::info('Incoming appointment request:', $request->all());
    
        // Validate the request data
        $validatedData = $request->validate([
            'vaccinationId' => 'required|exists:vaccination_records,vaccinationId',
            'vaccineType' => 'required|string', // ✅ Added validation for vaccineType
            'dateOfVisit' => 'required|date',
            'weight' => 'required|numeric|min:0',
            'height' => 'required|numeric|min:0',
            'temperature' => 'required|numeric|between:35,42',
            'antigenGiven' => 'required|string',
            'injectedBy' => 'required|string',
            'nextAppointment' => 'required|date|after:dateOfVisit',
            'exclusivelyBreastfed' => 'required|in:Yes,No,None',
        ]);
    
        try {
            // Create and save the appointment, including vaccineType
            VaccineAppointment::create($validatedData);
    
            // Log success
            \Log::info('Appointment saved successfully', ['data' => $validatedData]);
    
            // Redirect with a success message
            return redirect()->back()->with('success', 'Appointment scheduled successfully');
        } catch (\Exception $e) {
            // Log error details
            \Log::error('Failed to schedule appointment', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return redirect()->back()->with('error', 'Failed to schedule appointment. Please try again.');
        }
    }
    


    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,missed'
        ]);

        try {
            $appointment = VaccineAppointment::findOrFail($id);
            $appointment->update([
                'status' => $validatedData['status']
            ]);

            return redirect()->back()->with('success', 'Appointment status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update appointment status');
        }
    }

    /**
     * Get appointments for a specific vaccination record
     */
    public function getAppointmentsForVaccination($id)
    {
        try {
            $appointments = VaccineAppointment::where('vaccinationId', $id)
                ->orderBy('dateOfVisit', 'desc')
                ->get()
                ->map(function ($appointment) {
                    return [
                        'vacAppointmentId' => $appointment->vacAppointmentId,
                        'dateOfVisit' => $appointment->dateOfVisit,
                        'weight' => $appointment->weight,
                        'height' => $appointment->height,
                        'temperature' => $appointment->temperature,
                        'antigenGiven' => $appointment->antigenGiven,
                        'injectedBy' => $appointment->injectedBy,
                        'exclusivelyBreastfed' => $appointment->exclusivelyBreastfed,
                        'nextAppointment' => $appointment->nextAppointment
                    ];
                });

            return response()->json([
                'success' => true,
                'appointments' => $appointments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch appointments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointments history for a specific vaccination record
     */
    public function getHistory($vaccinationId)
    {
        try {
            $appointments = VaccineAppointment::where('vaccinationId', $vaccinationId)
                ->orderBy('dateOfVisit', 'desc')
                ->get()
                ->map(function ($appointment) {
                    return [
                        'vacAppointmentId' => $appointment->vacAppointmentId,
                        'dateOfVisit' => $appointment->dateOfVisit,
                        'weight' => $appointment->weight,
                        'height' => $appointment->height,
                        'temperature' => $appointment->temperature,
                        'antigenGiven' => $appointment->antigenGiven,
                        'injectedBy' => $appointment->injectedBy,
                        'exclusivelyBreastfed' => $appointment->exclusivelyBreastfed,
                        'nextAppointment' => $appointment->nextAppointment
                    ];
                });

            return response()->json([
                'history' => $appointments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch appointment history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
