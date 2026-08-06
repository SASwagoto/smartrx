<?php

use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientVisitController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(UserController::class)->prefix('users')->middleware('permission:manage-users')->group(function () {
        Route::get('/', 'index')->name('users.index');
        Route::post('/', 'store')->name('users.store');
        Route::get('/{user}/edit', 'edit')->name('users.edit');
        Route::patch('/{user}', 'update')->name('users.update');
        Route::delete('/{user}', 'destroy')->name('users.destroy');
    });

    Route::controller(RolePermissionController::class)->prefix('roles')->middleware('permission:manage-roles')->group(function () {
        Route::get('/', 'index')->name('roles.index');
        Route::post('/', 'store')->name('roles.store');
        Route::get('/{role}/edit', 'edit')->name('roles.edit');
        Route::patch('/{role}', 'update')->name('roles.update');
        Route::delete('/{role}', 'destroy')->name('roles.destroy');
    });

    Route::controller(PatientController::class)->prefix('patients')->group(function () {
        Route::get('/', 'index')->middleware('permission:view-patients')->name('patients.index');
        Route::get('/create', 'create')->middleware('permission:create-patients')->name('patients.create');
        Route::post('/', 'store')->middleware('permission:create-patients')->name('patients.store');
        Route::get('/{patient}', 'show')->middleware('permission:view-patients')->name('patients.show');
        Route::get('/{patient}/edit', 'edit')->middleware('permission:update-patients')->name('patients.edit');
        Route::patch('/{patient}', 'update')->middleware('permission:update-patients')->name('patients.update');
        Route::delete('/{patient}', 'destroy')->middleware('permission:delete-patients')->name('patients.destroy');
    });

    Route::controller(PatientVisitController::class)->group(function () {
        Route::post('/visit/store', 'store')->name('visits.store');
        Route::patch('/visit/{visit}/update', 'update')->name('visits.update');
        Route::patch('/visit/{visit}/complete', 'complete')->name('visits.complete');
        Route::post('/visits/{visit}/upload-document', 'uploadDocument')->name('visits.upload-document');
        Route::get('/search/symptoms', 'searchSymptoms')->name('search.symptoms');
        Route::get('/clinical-findings/search', 'searchClinicalFindings')->name('clinical-findings.search');
        Route::patch('/visits/{visit}/auto-save', 'autoSave')->name('visits.auto-save');
    });

    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('/', 'index')->name('products.index');
        Route::post('/sync-medicines', [ProductController::class, 'syncProducts'])->name('sync.medicines')->middleware('permission:Sync-pharmacy-stock');
        Route::get('/search-medicines', [ProductController::class, 'searchMedicines'])->name('medicines.search');
    });

    Route::controller(PrescriptionController::class)->prefix('prescriptions')->group(function () {
        Route::get('/', 'index')->name('prescriptions.index')->middleware('permission:view-prescriptions');
        Route::get('/{prescription}/print', 'print')->name('prescriptions.print');
        Route::get('/create', 'create')->name('prescriptions.create')->middleware('permission:write-prescription');
        Route::post('/', 'store')->name('prescriptions.store')->middleware('permission:write-prescription');
        Route::get('/{prescription}', 'show')->name('prescriptions.show')->middleware('permission:view-prescriptions');
        Route::get('/{prescription}/edit', 'edit')->name('prescriptions.edit')->middleware('permission:edit-prescriptions');
        Route::put('/{prescription}', 'update')->name('prescriptions.update')->middleware('permission:edit-prescriptions');
        Route::delete('/{prescription}', 'destroy')->name('prescriptions.destroy')->middleware('permission:delete-prescriptions');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
