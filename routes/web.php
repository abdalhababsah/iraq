<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\AdminCandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Candidate Management
    Route::post('/candidates/import', [AdminCandidateController::class, 'importCandidates'])->name('candidates.import');
    Route::get('/candidates/export', [AdminCandidateController::class, 'exportCandidates'])->name('candidates.export');
    Route::get('/candidates/download-template', [AdminCandidateController::class, 'downloadTemplate'])->name('candidates.download-template');
    Route::get('/candidates/import-report', [AdminCandidateController::class, 'downloadImportReport'])->name('candidates.import-report');

    Route::get('/candidates', [AdminCandidateController::class, 'candidates'])->name('candidates.index');

    // ⚠️ CRITICAL: This MUST come BEFORE the {candidate} route
    Route::get('/candidates/create', [AdminCandidateController::class, 'create'])->name('candidates.create');

    // Now the parameterized routes
    Route::get('/candidates/{candidate}', [AdminCandidateController::class, 'showCandidate'])->name('candidates.show');
    Route::get('/candidates/{candidate}/edit', [AdminCandidateController::class, 'editCandidate'])->name('candidates.edit');
    Route::put('/candidates/{candidate}', [AdminCandidateController::class, 'updateCandidate'])->name('candidates.update');
    Route::post('/candidates', [AdminCandidateController::class, 'storeCandidate'])->name('candidates.store');
    Route::patch('/candidates/{candidate}/toggle-status', [AdminCandidateController::class, 'toggleCandidateStatus'])->name('candidates.toggle-status');
    Route::delete('/candidates/{candidate}', [AdminCandidateController::class, 'deleteCandidate'])->name('candidates.delete');

    // Image management routes for admin
    Route::delete('/candidates/{candidate}/remove-profile-image', [AdminCandidateController::class, 'removeCandidateProfileImage'])->name('candidates.remove-profile-image');
    Route::delete('/candidates/{candidate}/remove-banner-image', [AdminCandidateController::class, 'removeCandidateProfileBannerImage'])->name('candidates.remove-banner-image');

    // Education Routes - 
    Route::post('/candidates/{candidate}/education', [AdminCandidateController::class, 'addEducation'])->name('candidates.education.add');
    Route::get('/candidates/{candidate}/education/{education}/edit', [AdminCandidateController::class, 'editEducation'])->name('candidates.education.edit');
    Route::put('/candidates/{candidate}/education/{education}', [AdminCandidateController::class, 'updateEducation'])->name('candidates.education.update');
    Route::delete('/candidates/{candidate}/education/{education}', [AdminCandidateController::class, 'deleteEducation'])->name('candidates.education.delete');


    // Admin Management Routes (add inside the admin middleware group)
    Route::get('/admins', [AdminController::class, 'admins'])->name('admins.index');
    Route::post('/admins', [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::put('/admins/{admin}', [AdminController::class, 'updateAdmin'])->name('admins.update');
    Route::patch('/admins/{admin}/toggle-status', [AdminController::class, 'toggleAdminStatus'])->name('admins.toggle-status');
    Route::delete('/admins/{admin}', [AdminController::class, 'deleteAdmin'])->name('admins.delete');
});

require __DIR__ . '/auth.php';