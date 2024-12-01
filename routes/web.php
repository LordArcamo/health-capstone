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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');

// routes/web.php

Route::get('/patients', [PatientController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/itr', CheckUpController::class);
    Route::post('/itr/store', [CheckUpController::class, 'store'])->name('itr.store');
    Route::get('/checkup/itr', [CheckUpController::class, 'create'])->name('itr'); 
    Route::get('/patients/itrtable', [CheckUpController::class, 'index'])->name('itr.index');
    
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/nationalimmunizationprogram', NationalImmunizationProgramController::class);
    Route::get('/checkup/nationalimmunizationprogram', [NationalImmunizationProgramController::class, 'create'])->name('nationalimmunizationprogram');
    Route::post('/nationalimmunizationprogram/store', [NationalImmunizationProgramController::class, 'store'])->name('nationalimmunizationprogram.store');
    Route::get('/patients/epi-records', [NationalImmunizationProgramController::class, 'index'])->name('nationalimmunizationprogram.index');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/prenatal', PreNatalController::class);
    Route::get('/checkup/prenatal', [PreNatalController::class, 'create'])->name('prenatal');
    Route::post('/prenatal/store', [PreNatalController::class, 'store'])->name('prenatal.store');
    Route::get('/patients/prenatal-postpartum', [PreNatalController::class, 'index'])->name('prenatal-postpartum.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
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

Route::get('/record-cases', function () {
    return Inertia::render('Record-Cases');
})->middleware(['auth', 'verified'])->name('record-cases');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
