<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\CheckUp;
use App\Models\PersonalInformation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SessionController extends Controller
{
    // Fetch all sessions and return to Inertia
    public function index()
    {
        // Fetch data for mental health patients by joining ITR with PersonalInformation
        return$data = PersonalInformation::join('itr', 'personal_information.personalId', '=', 'itr.personalId')
            ->where('itr.visitType', 'Mental Health') // Filter for mental health visit types
            ->select(
                'personal_information.personalId',
                'personal_information.firstName',
                'personal_information.lastName',
                'personal_information.age',
                'personal_information.barangay',
                'itr.visitType',
                'itr.diagnosis'
            )
            ->distinct() // Ensure no duplicate entries
            ->get();

        // Pass the fetched data to the frontend
        return Inertia::render('Services/MentalHealth', [
            'MENTALHEALTH' => $data,
        ]);
    }

    
    // Store a new session
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'nullable|string|in:active,finished',
        ]);

        $validated['status'] = $validated['status'] ?? 'active'; // Default status is "active"
        Session::create($validated);

        return redirect()->route('mental-health.sessions.index')
            ->with('success', 'Session created successfully!');
    }
    
    public function create(Request $request)
    {
        return Inertia::render('Services/MentalHealth');
    }

    // Update an existing session
    public function update(Request $request, $id)
    {
        $session = Session::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'status' => 'nullable|string|in:active,finished',
        ]);

        $session->update($validated);

        return redirect()->route('mental-health.sessions.index')
            ->with('success', 'Session updated successfully!');
    }

    // Destroy a session
    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();

        return redirect()->route('mental-health.sessions.index')
            ->with('success', 'Session deleted successfully!');
    }
}
