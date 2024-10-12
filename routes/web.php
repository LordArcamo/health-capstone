<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckUpController;
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

Route::resource('/checkup', CheckUpController::class);

// routes/web.php

Route::get('/patients', [PatientController::class, 'index']);


Route::get('/mortality', function () {
    return Inertia::render('Mortality');
})->middleware(['auth', 'verified'])->name('mortality');


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/patients', function () {
    return Inertia::render('Patients');
})->middleware(['auth', 'verified'])->name('patients');

Route::get('/itr', function () {
    return Inertia::render('IndividualTreatmentRecord');
})->middleware(['auth', 'verified'])->name('itr');

Route::get('/prenatal', function () {
    return Inertia::render('PreNatal');
})->middleware(['auth', 'verified'])->name('prenatal');

Route::get('/nationalimmunization', function () {
    return Inertia::render('NationalImmunization');
})->middleware(['auth', 'verified'])->name('nationalimmunization');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
