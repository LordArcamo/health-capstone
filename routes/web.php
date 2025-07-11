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
use App\Http\Controllers\SystemAnalyticsController;
use App\Http\Controllers\PersonalInformationController;
use App\Http\Controllers\RiskManagementController;
use App\Http\Controllers\VaccineAppointmentController;
use App\Http\Controllers\AuthorizationRolesController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\DoctorCheckupController;
use App\Http\Controllers\DoctorPreCheckupController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

use App\Http\Middleware\RoleMiddleware;


Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

// Handle form submit
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/checkup/thank-you/itr', function () {
    return Inertia::render('ThankYou/ThankYouItr');
})->middleware(['auth', 'verified'])->name('thank-you');


Route::get('/checkup/thank-you/prenatal', function () {
    return Inertia::render('ThankYou/ThankYouPrenatal');
})->middleware(['auth', 'verified'])->name('thank-you');

Route::get('/checkup/thank-you/nationalimmunization', function () {
    return Inertia::render('ThankYou/ThankYouNational');
})->middleware(['auth', 'verified'])->name('thank-you');



// Route for displaying the disease table
Route::get('/patients/disease-table', [PatientController::class, 'disease'])->name('disease.table');


// Route for filtering records by diagnosis
Route::get('/patients/disease-table/filter', [PatientController::class, 'disease'])->name('disease.filter');

Route::get('/', [AuthenticatedSessionController::class, 'index'])->name('home');

Route::get('/login', function () {
    return Inertia::render('Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/register', [RegisteredUserController::class, 'create'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register');

    Route::get('/admin-dashboard', [RegisteredUserController::class, 'getTotalPatients'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.totalPatients');

    Route::post('/admin/register', [RegisteredUserController::class, 'store'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register.store');

    Route::get('/staff', [RegisteredUserController::class, 'getStaff'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register.staff');

        Route::get('/inactiveStaff', [RegisteredUserController::class, 'getInactiveUsers'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register.inactiveStaff');

    Route::get('/doctor-checkup/itr', [RegisteredUserController::class, 'getItrDoctorCheckup'])
        ->middleware([RoleMiddleware::class . ':doctor'])
        ->name('doctor.itr-checkup');

    Route::put('/admin/staff/{id}', [RegisteredUserController::class, 'update'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register.update');

    Route::delete('/admin/staff/{id}', [RegisteredUserController::class, 'deactivate'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register.deactivate');

    Route::delete('/admin/inactiveStaff/{id}', [RegisteredUserController::class, 'activate'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register.activate');

    Route::get('/staff/active', [RegisteredUserController::class, 'getActiveUsers'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('admin.register.getActiveUsers');

});





Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');

// routes/web.php

Route::get('/patients', [PatientController::class, 'show']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/itr', CheckUpController::class);
    Route::post('/itr/store', [CheckUpController::class, 'store'])->name('itr.store');
    Route::post('/consultationDetails/store', [CheckUpController::class, 'storeDetails'])->name('consultationDetails.store');
    Route::get('/checkup/itr', [CheckUpController::class, 'create'])->name('itr');
    Route::get('/services/patients/itrtable', [CheckUpController::class, 'index'])->name('itr.index');
    Route::post('/services/patients/itrtable', [CheckUpController::class, 'import'])->name('itr.import');
});

Route::get('/services/patients', [PatientController::class, 'show'])->name('totalPatients.show');

Route::get('/services/queue', [PatientController::class, 'show'])->name('totalPatients.showQueu');

// Route::get('/services/consultation', [PatientController::class, 'show'])->name('totalPatients.showTodayConsultation');
Route::get('/services/critical', [PatientController::class, 'show'])->name('totalPatients.showCritical');




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
    Route::post('/prenatalConstultationDetails/store', [PreNatalController::class, 'storeDetails'])->name('prenatalConstultationDetails.store');
    Route::get('/services/patients/prenatal-postpartum', [PreNatalController::class, 'index'])->name('prenatal-postpartum.index');
    Route::post('/services/patients/prenatal-postpartum', [PreNatalController::class, 'import'])->name('prenatal-postpartum.import');
    Route::get('/prenatal/{prenatalConsultationDetailsID}/trimester/{trimester}', [PreNatalController::class, 'fetchTrimesterData']);
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/postpartum', PostpartumController::class);
    Route::get('/checkup/postpartum', [PostpartumController::class, 'create'])->name('postpartum');
    Route::post('/postpartum/store', [PostpartumController::class, 'store'])->name('postpartum.store');
    Route::get('/postpartum/{id}', [PostpartumController::class, 'show'])->name('postpartum.show');
    Route::put('/postpartum/{id}', [PostpartumController::class, 'update'])->name('postpartum.update');
    Route::get('/postpartum/data/{prenatalConsultationDetailsID}', [PostpartumController::class, 'getByprenatalConsultationDetailsID'])->name('postpartum.getByprenatalConsultationDetailsID');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/trimester1/store', [Trimester1Controller::class, 'store'])->name('trimester1.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/trimester2/store', [Trimester2Controller::class, 'store'])->name('trimester2.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/trimester3-4-5/store', [Trimester3Controller::class, 'store'])->name('trimester3.store');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/services/mental-health', [SessionController::class, 'index'])->name('mental-health.index');

    // Mental Health Session Management
    Route::post('/mental-health-sessions', [SessionController::class, 'store']);
    Route::put('/mental-health-sessions/{id}', [SessionController::class, 'update']);
    Route::delete('/mental-health-sessions/{id}', [SessionController::class, 'destroy']);
    Route::put('/mental-health-sessions/{id}/status', [SessionController::class, 'updateStatus']);
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/vaccination', VaccineController::class);
    Route::get('/services/vaccination', [VaccineController::class, 'handle'])->name('vaccine.handle');
    Route::get('/services/vaccination-create', [VaccineController::class, 'create'])->name('vaccine.create');
    Route::get('/patients/search-vaccine', [VaccineController::class, 'search'])->name('risk.search');
    Route::get('/patients/{personalId}', [VaccineController::class, 'show'])->name('patients.show');
    Route::post('/vaccination/store', [VaccineController::class, 'store'])->name('vaccination.store');
    Route::get('/vaccination/history/{personalId}', [VaccineController::class, 'getHistory'])->name('vaccination.history');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/services/risk-management', [RiskManagementController::class, 'handle'])->name('risk-management.handle');
    Route::get('/patients/search-risk', [RiskManagementController::class, 'search'])->name('risk.search');
    Route::get('/patients/{personalId}', [RiskManagementController::class, 'show'])->name('risk.show');
    Route::post('/risk-management/store', [RiskManagementController::class, 'store'])->name('risk-management.store');
    Route::post('/risk-management{personalId}', [RiskManagementController::class, 'destroy'])->name('risk-management.destroy');

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

Route::get('/admin-dashboard', [AuthorizationRolesController::class, 'admin'])
    ->middleware(['auth', 'verified', RoleMiddleware::class . ':admin'])
    ->name('admin.dashboard');

// Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register')
        ->middleware([RoleMiddleware::class . ':admin']);
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware([RoleMiddleware::class . ':admin'])
        ->name('register.store');
});

// Doctor Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/doctor-dashboard', [DoctorDashboardController::class, 'index'])
        ->middleware(RoleMiddleware::class . ':doctor')
        ->name('doctor.dashboard');
    Route::post('/doctor/mark-as-cancelled', [DoctorDashboardController::class, 'markAsCancelled'])
        ->middleware(RoleMiddleware::class . ':doctor')
        ->name('doctor.mark-as-cancelled');
    Route::get('/services/consultation', [DoctorDashboardController::class, 'getConsultation'])
        ->middleware(RoleMiddleware::class . ':doctor')
        ->name('doctor.getConsultation');
});

Route::get('/doctor-checkup/itr', [DoctorCheckupController::class, 'create'])
    ->middleware(RoleMiddleware::class . ':doctor') // Ensure only doctors can access
    ->name('doctor.itrdashboard');

Route::get('/doctor-checkup/prenatal', [DoctorPreCheckupController::class, 'create'])
    ->middleware(RoleMiddleware::class . ':doctor') // Ensure only doctors can access
    ->name('doctor.prenataldashboard');

Route::POST('/store/itr', [DoctorCheckupController::class, 'store'])
    ->middleware(RoleMiddleware::class . ':doctor') // Ensure only doctors can access
    ->name('store.itr');

Route::POST('/store/prenatal', [DoctorPreCheckupController::class, 'store'])
    ->middleware(RoleMiddleware::class . ':doctor') // Ensure only doctors can access
    ->name('store.prenatal');

Route::get('/doctor-checkup/{id}', [DoctorDashboardController::class, 'checkup'])
    ->middleware(RoleMiddleware::class . ':doctor')
    ->name('doctor.checkup');

// Route to display a tailored doctor dashboard with user data
Route::get('/doctor-dashboard/user', [DoctorDashboardController::class, 'doctor'])
    ->middleware(RoleMiddleware::class . ':doctor') // Ensure only doctors can access
    ->name('doctor.dashboard.user');

Route::get('/doctor-checkup', [DoctorCheckupController::class, 'index'])
    ->middleware(RoleMiddleware::class . ':doctor') // Restrict access to doctors
    ->name('ITRConsultation.checkup');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/itr-table', [DoctorCheckupController::class, 'index'])->name('ITR.index');
});


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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/system-analytics', [SystemAnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics-barchart', [PatientController::class, 'getPatientStatistics'])->name('analytics.barchart');
    Route::get('/analytics-piechart', [SystemAnalyticsController::class, 'getReferredPatientsStatistics'])->name('analytics.piechart');
    Route::get('/analytics-lineChart', [SystemAnalyticsController::class, 'getVaccinationStatistics'])->name('analytics.lineChart');
    Route::get('/analytics-line2Chart', [SystemAnalyticsController::class, 'getCasesStatistics'])->name('analytics.lineChart2');
    Route::get('/analytics-radarChart', [SystemAnalyticsController::class, 'getMentalHealthStatistics'])->name('analytics.radarChart');
    Route::get('/total-patients-data', [SystemAnalyticsController::class, 'getTotalPatientsData'])->name('total-patients.data');
});

    Route::get('/mental-health', function () {
        return Inertia::render('About/MentalDescription');
    })->middleware(['auth', 'verified'])->name('mental-health');

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api/appointments/vaccination/{id}', [VaccineAppointmentController::class, 'getAppointmentsForVaccination']);
    Route::post('/appointments/store', [VaccineAppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/appointments/{id}', [VaccineAppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/appointments/{id}', [VaccineAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/history/{vaccinationId}', [VaccineAppointmentController::class, 'getHistory']);
    Route::get('/appointments/vaccination/{id}', [VaccineAppointmentController::class, 'getAppointmentsForVaccination']);
});

require __DIR__.'/auth.php';
