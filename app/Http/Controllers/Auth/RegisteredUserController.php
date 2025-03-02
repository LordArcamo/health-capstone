<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
        $data = User::where('role', '!=', 'admin')->get();
    
        return Inertia::render('Admin/Staff', [
            'USERS' => $data,
        ]);
    }
    public function getItrDoctorCheckup()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }
    
            // Get fresh user data from the database
            $freshUser = DB::table('users')
            ->select('id', 'email', 'first_name', 'middle_name', 'last_name', 'specialization', 'prc_number', 'role')
            ->where('id', $user->id)
            ->first();
            if (!$freshUser) {
                \Log::error('User not found in database', ['id' => $user->id]);
                return redirect()->route('login');
            }
    
            // Check if the user has the role of 'doctor'
            if ($freshUser->role !== 'doctor') {
                \Log::warning('Unauthorized access attempt', [
                    'id' => $freshUser->id, 
                    'role' => $freshUser->role
                ]);
                return redirect()->route('unauthorized');
            }
    
            \Log::info('Fresh user data:', ['user' => $freshUser]);
    
            // Pass necessary fields explicitly
            return Inertia::render('Doctor/ItrDoctorCheckup', [
                'auth' => [
                    'user' => [
                        'id' => $freshUser->id,
                        'email' => $freshUser->email,
                        'first_name' => $freshUser->first_name ?? '',
                        'middle_name' => $freshUser->middle_name ?? '',
                        'last_name' => $freshUser->last_name ?? '',
                        'specialization' => $freshUser->specialization ?? '',
                        'prc_number' => $freshUser->prc_number ?? '',
                        'role' => $freshUser->role ?? 'doctor'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getItrDoctorCheckup:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
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
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:staff,doctor,admin',
            'phone' => 'required|string|max:13',
            'purok' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'profile_picture' => 'nullable|string', // Validate Base64 string
            'permissions' => 'array',
        ];

        // Add doctor-specific validation rules only if role is doctor
        if ($request->role === 'doctor') {
            $validationRules['prc_number'] = 'required|string|max:255';
            $validationRules['specialization'] = 'required|string|max:255';
            $validationRules['prc_validity'] = 'required|date';
        }

        // Validate the request
        $validatedData = $request->validate($validationRules);

        // Store the Base64 string directly in the database
        $userData = [
            'first_name' => $validatedData['first_name'],
            'middle_name' => $validatedData['middle_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'phone' => $validatedData['phone'],
            'purok' => $validatedData['purok'],
            'barangay' => $validatedData['barangay'],
            'city' => $validatedData['city'],
            'profile_picture' => $validatedData['profile_picture'], // Save Base64 string
            'permissions' => $validatedData['permissions'] ?? [],
            'prc_number' => $validatedData['prc_number'] ?? 'None',
            'specialization' => $validatedData['specialization'] ?? 'None',
            'prc_validity' => $validatedData['prc_validity'] ?? null,
        ];

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
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:staff,doctor',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|in:view_patients,create_records,edit_records,delete_records', // Adjust permission options as needed
        ]);

        // Prevent updating to admin role
        if ($request->role === 'admin') {
            return response()->json(['message' => 'Unauthorized role assignment.'], 403);
        }

        // Update user fields
        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Update permissions if provided
        if ($request->has('permissions')) {
            $user->permissions = $request->permissions; // Assuming 'permissions' is a JSON column in the 'users' table
            $user->save();
        }

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
    public function getActiveUsers()
    {
        $activeUsers = DB::table('sessions')
        ->join('users', 'sessions.user_id', '=', 'users.id')
        ->whereNotNull('sessions.user_id') // Ensure user is logged in
        ->where('users.role', '!=', 'admin') // Exclude admins
        ->distinct('sessions.user_id') // Unique users
        ->select('users.*') // Select all attributes
        ->get()
        ->map(function ($user) {
            $user->permissions = json_decode($user->permissions, true) ?? []; // Ensure it's an array
            return $user;
        });
    
        return Inertia::render('Admin/ActiveUser', [
            'activeUsers' => $activeUsers,
        ]);
    }
}
