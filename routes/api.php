<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiCandidateController;
use App\Http\Controllers\Api\CandidateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Candidate API Routes
Route::group(['prefix' => 'candidates'], function () {
    // Get all candidates with optional search and filters
    Route::get('/', [CandidateController::class, 'index'])->name('api.candidates.index');
    
    Route::post('/', [CandidateController::class, 'store'])->name('api.candidates.store');

    // Get specific candidate by ID
    Route::get('/{id}', [CandidateController::class, 'show'])->name('api.candidates.show')->where('id', '[0-9]+');
});