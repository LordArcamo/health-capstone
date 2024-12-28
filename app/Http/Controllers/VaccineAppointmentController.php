<?php

namespace App\Http\Controllers;

use App\Models\VaccineAppointment;
use App\Models\PersonalInformation;
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
        $appointments = VaccineAppointment::with('patient')
            ->orderBy('appointmentDate', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->appointmentId,
                    'patientId' => $appointment->personalId,
                    'firstName' => $appointment->patient->firstName,
                    'middleName' => $appointment->patient->middleName,
                    'lastName' => $appointment->patient->lastName,
                    'suffix' => $appointment->patient->suffix,
                    'age' => $appointment->patient->age,
                    'vaccineType' => $appointment->vaccineType,
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
        $validated = $request->validate([
            'personalId' => 'required|exists:personal_information,personalId',
            'appointmentDate' => 'required|date|after_or_equal:today',
            'vaccineType' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $appointment = VaccineAppointment::create([
            'personalId' => $validated['personalId'],
            'appointmentDate' => $validated['appointmentDate'],
            'vaccineType' => $validated['vaccineType'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'scheduled',
            'createdBy' => Auth::id()
        ]);

        return redirect()->back();
    }

    /**
     * Update an appointment's status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $appointment = VaccineAppointment::findOrFail($id);
        $appointment->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $appointment->notes,
            'updatedBy' => Auth::id()
        ]);

        return redirect()->back();
    }

    /**
     * Get upcoming appointments for a patient.
     */
    public function getUpcomingForPatient($personalId)
    {
        $appointments = VaccineAppointment::where('personalId', $personalId)
            ->where('status', 'scheduled')
            ->where('appointmentDate', '>=', now())
            ->orderBy('appointmentDate', 'asc')
            ->get();

        return Inertia::render('Patients/Show', [
            'upcomingAppointments' => $appointments
        ]);
    }

    /**
     * Cancel an appointment.
     */
    public function cancel($id)
    {
        $appointment = VaccineAppointment::findOrFail($id);
        $appointment->update([
            'status' => 'cancelled',
            'updatedBy' => Auth::id()
        ]);

        return redirect()->back();
    }

    /**
     * Get latest appointment for a patient
     */
    public function getLatestAppointment($personalId)
    {
        $latestAppointment = VaccineAppointment::where('personalId', $personalId)
            ->orderBy('appointmentDate', 'desc')
            ->first();

        if ($latestAppointment) {
            return response()->json([
                'success' => true,
                'appointment' => [
                    'appointmentDate' => $latestAppointment->appointmentDate,
                    'vaccineType' => $latestAppointment->vaccineType,
                    'notes' => $latestAppointment->notes,
                    'status' => $latestAppointment->status
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'appointment' => null
        ]);
    }
}
