<?php

namespace App\Http\Controllers;

use App\Models\MentalHealthSession;
use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SessionController extends Controller
{
    // Fetch all sessions and return to Inertia
    public function index()
    {
        // Fetch data for mental health patients by joining ITR with PersonalInformation
        $patients = PersonalInformation::join('itr', 'personal_information.personalId', '=', 'itr.personalId')
            ->where('itr.visitType', 'Mental Health') // Filter for mental health visit type
            ->select(
                'personal_information.personalId',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.age',
                'personal_information.barangay',
                'personal_information.purok',
                'itr.visitType',
                'itr.diagnosis',
                'itr.created_at as visitDate'
            )
            ->orderBy('itr.created_at', 'desc')
            ->get();

        // Fetch sessions
        $sessions = MentalHealthSession::orderBy('date', 'desc')->get();

        // Log the data for debugging
        \Log::info('Mental health patients data:', ['count' => $patients->count(), 'data' => $patients->toArray()]);

        return Inertia::render('Services/MentalHealth', [
            'patients' => $patients,
            'sessions' => $sessions,
        ]);
    }

    // Store a new session
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:active,finished'
        ]);

        MentalHealthSession::create($validated);

        return redirect()->back()
            ->with('success', 'Session created successfully')
            ->with('sessions', MentalHealthSession::orderBy('date', 'desc')->get());
    }

    // Update an existing session
    public function update(Request $request, $id)
    {
        $session = MentalHealthSession::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:active,finished'
        ]);

        $session->update($validated);

        return redirect()->back()
            ->with('success', 'Session updated successfully')
            ->with('sessions', MentalHealthSession::orderBy('date', 'desc')->get());
    }

    // Destroy a session
    public function destroy($id)
    {
        $session = MentalHealthSession::findOrFail($id);
        $session->delete();

        return redirect()->back()
            ->with('success', 'Session deleted successfully')
            ->with('sessions', MentalHealthSession::orderBy('date', 'desc')->get());
    }

    // Update session status
    public function updateStatus(Request $request, $id)
    {
        $session = MentalHealthSession::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:active,finished'
        ]);

        $session->update($validated);

        return redirect()->back()
            ->with('success', 'Session status updated successfully')
            ->with('sessions', MentalHealthSession::orderBy('date', 'desc')->get());
    }
}
