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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Candidate Management
    Route::post('/candidates/import', [AdminController::class, 'importCandidates'])->name('candidates.import');
    Route::get('/candidates/export', [AdminController::class, 'exportCandidates'])->name('candidates.export');
    Route::get('/candidates/download-template', [AdminController::class, 'downloadTemplate'])->name('candidates.download-template');
    Route::get('/candidates/import-report', [AdminController::class, 'downloadImportReport'])->name('candidates.import-report'); // ADD THIS LINE

    Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates.index');
    Route::get('/candidates/{candidate}', [AdminController::class, 'showCandidate'])->name('candidates.show');
    Route::get('/candidates/{candidate}/edit', [AdminController::class, 'editCandidate'])->name('candidates.edit');
    Route::patch('/candidates/{candidate}', [AdminController::class, 'updateCandidate'])->name('candidates.update');
    Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('candidates.store');
    Route::patch('/candidates/{candidate}/toggle-status', [AdminController::class, 'toggleCandidateStatus'])->name('candidates.toggle-status');
    Route::delete('/candidates/{candidate}', [AdminController::class, 'deleteCandidate'])->name('candidates.delete');
});

// Candidate Routes
Route::middleware(['auth', 'role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [CandidateController::class, 'profile'])->name('profile');
    Route::patch('/profile', [CandidateController::class, 'updateProfile'])->name('profile.update');

    // Education Routes
    Route::get('/education', [CandidateController::class, 'education'])->name('education');
    Route::post('/education', [CandidateController::class, 'addEducation'])->name('education.add');
    Route::patch('/education/{education}', [CandidateController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/{education}', [CandidateController::class, 'deleteEducation'])->name('education.delete');
});

// API Routes (can be moved to api.php if preferred)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/candidates', function () {
        return \App\Models\Candidate::with(['user', 'constituency', 'education'])->get();
    })->name('candidates');

    Route::get('/candidates/{candidate}', function (\App\Models\Candidate $candidate) {
        return $candidate->load(['user', 'constituency', 'education']);
    })->name('candidates.show');
});

require __DIR__ . '/auth.php';