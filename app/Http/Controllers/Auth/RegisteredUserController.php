<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function getStaff()
    {
        // Fetch all users excluding the admin role
        $data = User::where('role', '!=', 'admin')->get();

        // Pass the filtered users to the Inertia view
        return Inertia::render('Admin/Staff', [
            'USERS' => $data,
        ]);
    }

    // public function index()
    // {
    //     // Count all users excluding the admin role
    //     $totalUsers = User::where('role', '!=', 'admin')->count();

    //     // Pass the user count to the Inertia view
    //     return Inertia::render('Admin/AdminDashboard', [
    //         'totalUsers' => $totalUsers,
    //     ]);
    // }

    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation rules
        $validationRules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:staff,doctor,admin',
            'phone' => 'required|string|max:13',
            'purok' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
            'permissions' => 'array',
        ];

        // Add doctor-specific validation rules only if role is doctor
        if ($request->role === 'doctor') {
            $validationRules['prc_number'] = 'required|string|max:255';
            $validationRules['specialization'] = 'required|string|max:255';
            $validationRules['prc_validity'] = 'required|date';
        }

        // Validate the request
        $request->validate($validationRules);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');
        }

        // Prepare user data
        $userData = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'purok' => $request->purok,
            'barangay' => $request->barangay,
            'city' => $request->city,
            'profile_picture' => $profilePicturePath,
            'permissions' => $request->permissions,
            'prc_number' => 'None',
            'specialization' => 'None',
            'prc_validity' => null,
        ];

        // Override with doctor-specific data if role is doctor
        if ($request->role === 'doctor') {
            $userData['prc_number'] = $request->prc_number;
            $userData['specialization'] = $request->specialization;
            $userData['prc_validity'] = $request->prc_validity;
        }

        // Create the user
        $user = User::create($userData);

        // Trigger the registered event
        event(new Registered($user));

        // Redirect to staff page with success message
        return redirect()->route('admin.register.staff')
            ->with('message', 'User registered successfully!');
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:staff,doctor',
        ]);

        // Prevent updating to admin role
        if ($request->role === 'admin') {
            return response()->json(['message' => 'Unauthorized role assignment.'], 403);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->back();
    }

    /**
     * Delete the specified user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting admin users
        if ($user->role === 'admin') {
            return response()->json(['message' => 'Cannot delete admin users.'], 403);
        }

        $user->delete();

        return redirect()->back();
    }
}
