<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckUpController;
use App\Http\Controllers\NationalImmunizationProgramController;
use App\Http\Controllers\PreNatalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PatientController;

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
    Route::get('//patients/itrtable', [CheckUpController::class, 'index'])->name('itr.index');
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


Route::get('/checkup', function () {
    return Inertia::render('Checkup');
})->middleware(['auth', 'verified'])->name('checkup');

Route::get('/checkup/prenatalpostpartum', function () {
    return Inertia::render('PrenatalPostpartum');
})->middleware(['auth', 'verified'])->name('prenatalpostpartum');

Route::get('/checkup/postpartum', function () {
    return Inertia::render('PostPartumCheckup');
})->middleware(['auth', 'verified'])->name('postpartum');


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
