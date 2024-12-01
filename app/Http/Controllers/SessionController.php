<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SessionController extends Controller
{
    // Fetch all sessions and return to Inertia
    public function index()
    {
        $sessions = Session::orderBy('id', 'desc')->get(); // Fetch sessions ordered by ID
        return Inertia::render('Services/MentalHealth', [
            'sessions' => $sessions,
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
