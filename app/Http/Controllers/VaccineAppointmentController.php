<?php

namespace App\Http\Controllers;

use App\Models\VaccineAppointment;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $validatedData = $request->validate([
            'vaccinationId' => 'required|exists:vaccination_records,vaccinationId',
            'appointmentDate' => 'required|date|after_or_equal:today',
            'vaccineType' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $appointment = VaccineAppointment::create([
                'vaccinationId' => $validatedData['vaccinationId'],
                'appointmentDate' => $validatedData['appointmentDate'],
                'vaccineType' => $validatedData['vaccineType'],
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'scheduled'
            ]);

            return redirect()->back()->with('success', 'Appointment scheduled successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to schedule appointment: ' . $e->getMessage());
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
                ->orderBy('appointmentDate', 'desc')
                ->get()
                ->map(function ($appointment) {
                    return [
                        'vacAppointmentId' => $appointment->vacAppointmentId,
                        'appointmentDate' => $appointment->appointmentDate,
                        'status' => $appointment->status,
                        'notes' => $appointment->notes,
                        'vaccineType' => $appointment->vaccineType
                    ];
                });

            return response()->json([
                'appointments' => $appointments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch appointments'
            ], 500);
        }
    }
}
