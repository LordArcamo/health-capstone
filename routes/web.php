<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckUpController;
use App\Http\Controllers\NationalImmunizationProgramController;
use Illuminate\Foundation\Application;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// routes/web.php

Route::get('/patients', [PatientController::class, 'index']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/itr', CheckUpController::class);
    Route::post('/itr/store', [CheckUpController::class, 'store'])->name('itr.store');
    Route::get('/checkup/itr', [CheckUpController::class, 'create'])->name('itr'); // corrected 'patiens' to 'patients'
    Route::get('/patients', [CheckUpController::class, 'index'])->name('itr.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/nationalimmunizationprogram', NationalImmunizationProgramController::class);
    Route::get('/checkup/nationalimmunizationprogram', [NationalImmunizationProgramController::class, 'create'])->name('nationalimmunizationprogram');
    Route::post('/nationalimmunizationprogram/store', [NationalImmunizationProgramController::class, 'store'])->name('nationalimmunizationprogram.store');
});

Route::get('/checkup', function () {
    return Inertia::render('Checkup');
})->middleware(['auth', 'verified'])->name('checkup');

<<<<<<< HEAD
=======
Route::get('/checkup/itr', function () {
    return Inertia::render('IndividualTreatmentRecordCheckup');
})->name('itr');

Route::get('/patients/itrtable', function () {
    return Inertia::render('IndividualTreatmentRecord');
})->name('itrtable');

Route::get('/patients/prenatal-postpartum', function () {
    return Inertia::render('PreNatal');
})->name('prenatal-postpartum');

Route::get('/patients/epi-records', function () {
    return Inertia::render('NationalImmunization');
})->name('epirecords');

Route::get('/checkup/nationalimmunizationprogram', function () {
    return Inertia::render('NationalImmunizationCheckup');
})->name('nationalimmunizationprogram');

>>>>>>> d157683049fd015b37059832b49ca82f03b28dcf
Route::get('/checkup/prenatal', function () {
    return Inertia::render('PreNatalCheckup');
})->name('prenatal');

Route::get('/mortality', function () {
    return Inertia::render('Mortality');
})->middleware(['auth', 'verified'])->name('mortality');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
