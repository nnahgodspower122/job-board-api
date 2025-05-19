<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;

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

Route::group(['prefix' => 'oauth'], function () {
    Route::post('token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
    Route::post('token/refresh', '\Laravel\Passport\Http\Controllers\TransientTokenController@refresh');
    Route::get('tokens', '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@forUser');
    Route::delete('tokens/{token_id}', '\Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController@destroy');
});


// Authentication Routes
Route::post('/company/register', [AuthController::class, 'registerCompany']);
Route::post('/candidate/register', [AuthController::class, 'registerCandidate']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/public/jobs', [JobController::class, 'index']);


// Protected Routes for Companies
Route::middleware('auth:company_api')->group(function () {
    Route::prefix('jobs')->group(function () {
        Route::post('/', [JobController::class, 'store']);
        Route::put('/{id}', [JobController::class, 'update']);
        Route::delete('/{id}', [JobController::class, 'destroy']);
        Route::get('/', [JobController::class, 'companyJobs']);
    });
    Route::get('/dashboard/company', [DashboardController::class, 'companyDashboard']);
});


// Protected Routes for Candidates
Route::middleware('auth:candidate_api')->group(function () {
    Route::post('/jobs/{id}/apply', [ApplicationController::class, 'apply']);
    Route::get('/dashboard/candidate', [DashboardController::class, 'candidateDashboard']);
});