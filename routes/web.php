<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckUpController;
use App\Http\Controllers\NationalImmunizationProgramController;
use App\Http\Controllers\PreNatalController;
use Illuminate\Foundation\Application;
use App\Models\PersonalInformation;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PostpartumController;
use App\Http\Controllers\Trimester1Controller;
use App\Http\Controllers\Trimester2Controller;
use App\Http\Controllers\Trimester3Controller;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\VaccineController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     // Resourceful routes for sessions (this includes index, create, store, show, edit, update, destroy)
//     Route::resource('/mental-health', SessionController::class);

//     // Optionally, you can add custom routes if needed, though it's usually unnecessary if using resourceful routes
//     // Custom route for storing a session (can be used for custom logic if necessary)
//     // Route::post('/mental-health/sessions/store', [SessionController::class, 'store'])->name('mental-health.sessions.store');

//     // If you want to show a session list for a specific page (usually the 'index' is already handled by resource route)
//     // Route::get('/services/mental-health/session', [SessionController::class, 'index'])->name('mental-health.sessions.index');
// });



Route::get('/', [AuthenticatedSessionController::class, 'index'])->name('home');

Route::get('/login', function () {
    return Inertia::render('Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::get('/', function () {
//     return Inertia::render('Home');
// })->name('home');


Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');

// routes/web.php

Route::get('/patients', [PatientController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/itr', CheckUpController::class);
    Route::post('/itr/store', [CheckUpController::class, 'store'])->name('itr.store');
    Route::get('/checkup/itr', [CheckUpController::class, 'create'])->name('itr'); 
    Route::get('/services/patients/itrtable', [CheckUpController::class, 'index'])->name('itr.index');
    Route::post('/services/patients/itrtable', [CheckUpController::class, 'import'])->name('itr.import');
});

Route::get('/services/patients', [PatientController::class, 'show'])->name('totalPatients.show');

Route::get('/services/patients/referred', [PatientController::class, 'showReferred'])->name('referredPatients.show');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/nationalimmunizationprogram', NationalImmunizationProgramController::class);
    Route::get('/checkup/nationalimmunizationprogram', [NationalImmunizationProgramController::class, 'create'])->name('nationalimmunizationprogram');
    Route::post('/nationalimmunizationprogram/store', [NationalImmunizationProgramController::class, 'store'])->name('nationalimmunizationprogram.store');
    Route::get('/services/patients/epi-records', [NationalImmunizationProgramController::class, 'index'])->name('nationalimmunizationprogram.index');
    Route::post('/services/patients/epi-records', [NationalImmunizationProgramController::class, 'import'])->name('nationalimmunizationprogram.import');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/prenatal', PreNatalController::class);
    Route::get('/checkup/prenatal', [PreNatalController::class, 'create'])->name('prenatal');
    Route::post('/prenatal/store', [PreNatalController::class, 'store'])->name('prenatal.store');
    Route::get('/services/patients/prenatal-postpartum', [PreNatalController::class, 'index'])->name('prenatal-postpartum.index');
    Route::post('/services/patients/prenatal-postpartum', [PreNatalController::class, 'import'])->name('prenatal-postpartum.import');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/postpartum', PostpartumController::class);
    Route::get('/checkup/postpartum', [PostpartumController::class, 'create'])->name('postpartum');
    Route::post('/postpartum/store', [PostpartumController::class, 'store'])->name('postpartum.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/trimester1/store', [Trimester1Controller::class, 'store'])->name('trimester1.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/trimester2/store', [Trimester2Controller::class, 'store'])->name('trimester2.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/trimester3/store', [Trimester3Controller::class, 'store'])->name('trimester3.store');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/services/mental-health', [SessionController::class, 'index'])->name('mental-health.index');
    Route::get('/services/mental-health', [SessionController::class, 'create'])->name('mentalhealth');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/vaccination', VaccineController::class);
    // Route::get('/services/vaccination', [VaccineController::class, 'index'])->name('vaccine.index');
    Route::get('/services/vaccination', [VaccineController::class, 'create'])->name('vaccine.create');
    Route::get('/patients/search', [VaccineController::class, 'search'])->name('patients.search');
    Route::get('/patients/{personalId}', [VaccineController::class, 'show'])->name('patients.show');
});


Route::get('/checkup', function () {
    // Retrieve all patients from the database
    $patients = PersonalInformation::all();

    // Pass patients to the Inertia page
    return Inertia::render('Checkup', [
        'patients' => $patients, // Pass the patients data
    ]);
})->middleware(['auth', 'verified'])->name('checkup');

Route::get('/patients/{personalId}', [PatientController::class, 'show'])->name('patients.show');

Route::get('/mortality', function () {
    return Inertia::render('Mortality');
})->middleware(['auth', 'verified'])->name('mortality');

Route::get('/itr-services', function () {
    return Inertia::render('About/ItrDescription');
})->middleware(['auth', 'verified'])->name('itr-services');

Route::get('/epi-records-services', function () {
    return Inertia::render('About/EpiDescription');
})->middleware(['auth', 'verified'])->name('epi-records');

Route::get('/prenatal-postpartum-services', function () {
    return Inertia::render('About/PrenatalDescription');
})->middleware(['auth', 'verified'])->name('prenatal-postpartum-services');

Route::get('/vaccination-services', function () {
    return Inertia::render('About/VaccinationDescription');
})->middleware(['auth', 'verified'])->name('vaccination-services');

Route::get('/risk-management-services', function () {
    return Inertia::render('About/RiskDescription');
})->middleware(['auth', 'verified'])->name('risk-management-services');

Route::get('/mental-health', function () {
    return Inertia::render('About/MentalDescription');
})->middleware(['auth', 'verified'])->name('mental-health');

Route::get('/system-analytics', function () {
    return Inertia::render('Analytics');
})->middleware(['auth', 'verified'])->name('system-analytics');

Route::get('/services/risk-management', function () {
    return Inertia::render('Services/RiskManagement');
})->middleware(['auth', 'verified'])->name('risk-management');

Route::get('/patients/cases', function () {
    return Inertia::render('Table/DiseaseCases');
})->middleware(['auth', 'verified'])->name('cases');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
