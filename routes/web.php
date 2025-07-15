<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth',])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Candidate Management
    Route::post('/candidates/import', [AdminController::class, 'importCandidates'])->name('candidates.import');
    Route::get('/candidates/export', [AdminController::class, 'exportCandidates'])->name('candidates.export');
    Route::get('/candidates/download-template', [AdminController::class, 'downloadTemplate'])->name('candidates.download-template');
    Route::get('/candidates/import-report', [AdminController::class, 'downloadImportReport'])->name('candidates.import-report');

    Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates.index');
    Route::get('/candidates/{candidate}', [AdminController::class, 'showCandidate'])->name('candidates.show');
    Route::get('/candidates/{candidate}/edit', [AdminController::class, 'editCandidate'])->name('candidates.edit');
    Route::put('/candidates/{candidate}', [AdminController::class, 'updateCandidate'])->name('candidates.update');
    Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('candidates.store');
    Route::patch('/candidates/{candidate}/toggle-status', [AdminController::class, 'toggleCandidateStatus'])->name('candidates.toggle-status');
    Route::delete('/candidates/{candidate}', [AdminController::class, 'deleteCandidate'])->name('candidates.delete');
    
    // Image management routes for admin
    Route::delete('/candidates/{candidate}/remove-profile-image', [AdminController::class, 'removeCandidateProfileImage'])->name('candidates.remove-profile-image');
    Route::delete('/candidates/{candidate}/remove-banner-image', [AdminController::class, 'removeCandidateProfileBannerImage'])->name('candidates.remove-banner-image');

    // Education Routes - FIXED
    Route::post('/candidates/{candidate}/education', [AdminController::class, 'addEducation'])->name('candidates.education.add');
    Route::get('/candidates/{candidate}/education/{education}/edit', [AdminController::class, 'editEducation'])->name('candidates.education.edit');
    Route::put('/candidates/{candidate}/education/{education}', [AdminController::class, 'updateEducation'])->name('candidates.education.update');
    Route::delete('/candidates/{candidate}/education/{education}', [AdminController::class, 'deleteEducation'])->name('candidates.education.delete');
});

// Candidate Routes
Route::middleware(['auth', 'role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CandidateController::class, 'profile'])->name('profile');
    Route::put('/profile', [CandidateController::class, 'updateProfile'])->name('profile.update');

    // Image management routes for candidates
    Route::delete('/profile/remove-image', [CandidateController::class, 'removeProfileImage'])->name('profile.remove-image');
    Route::delete('/profile/remove-banner', [CandidateController::class, 'removeProfileBannerImage'])->name('profile.remove-banner');

    // Education Routes
    Route::get('/education', [CandidateController::class, 'education'])->name('education');
    Route::post('/education', [CandidateController::class, 'addEducation'])->name('education.add');
    Route::put('/education/{education}', [CandidateController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/{education}', [CandidateController::class, 'deleteEducation'])->name('education.delete');
});


require __DIR__ . '/auth.php';