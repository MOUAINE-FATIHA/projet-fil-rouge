<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EvaluationController;
use App\Http\Controllers\API\InternshipController;
use App\Http\Controllers\API\InternshipOfferController;
use App\Http\Controllers\API\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — StageConnect
|--------------------------------------------------------------------------
|
| Toutes les routes sont préfixées par /api
|
*/

// ─── Auth (public) ────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// ─── Offres publiques ─────────────────────────────────────────────────────────
Route::get('/offers',        [InternshipOfferController::class, 'index']);
Route::get('/offers/{offer}', [InternshipOfferController::class, 'show']);

// ─── Routes authentifiées ─────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/logout',          [AuthController::class, 'logout']);
        Route::get('/me',               [AuthController::class, 'me']);
        Route::put('/password',         [AuthController::class, 'updatePassword']);
    });

    // ── Offres de stage ──────────────────────────────────────────────────────
    Route::post('/offers',               [InternshipOfferController::class, 'store']);
    Route::put('/offers/{offer}',        [InternshipOfferController::class, 'update']);
    Route::delete('/offers/{offer}',     [InternshipOfferController::class, 'destroy']);
    Route::get('/company/offers',        [InternshipOfferController::class, 'myOffers']);

    // ── Candidatures ─────────────────────────────────────────────────────────
    Route::get('/student/applications',                      [ApplicationController::class, 'myApplications']);
    Route::post('/offers/{offer}/apply',                     [ApplicationController::class, 'apply']);
    Route::delete('/applications/{application}/withdraw',    [ApplicationController::class, 'withdraw']);
    Route::get('/offers/{offer}/applications',               [ApplicationController::class, 'offerApplications']);
    Route::patch('/applications/{application}/status',       [ApplicationController::class, 'updateStatus']);

    // ── Stages ────────────────────────────────────────────────────────────────
    Route::get('/internships',                                   [InternshipController::class, 'index']);
    Route::get('/internships/{internship}',                      [InternshipController::class, 'show']);
    Route::patch('/internships/{internship}/status',             [InternshipController::class, 'updateStatus']);
    Route::patch('/internships/{internship}/assign-supervisor',  [InternshipController::class, 'assignSupervisor']);
    Route::post('/internships/{internship}/documents',           [InternshipController::class, 'uploadDocument']);
    Route::post('/internships/{internship}/feedback',            [InternshipController::class, 'studentFeedback']);

    // ── Évaluations ───────────────────────────────────────────────────────────
    Route::get('/internships/{internship}/evaluations',  [EvaluationController::class, 'index']);
    Route::post('/internships/{internship}/evaluations', [EvaluationController::class, 'store']);

    // ── Messagerie ────────────────────────────────────────────────────────────
    Route::get('/conversations',                                  [MessageController::class, 'conversations']);
    Route::post('/conversations',                                 [MessageController::class, 'createConversation']);
    Route::get('/conversations/{conversation}/messages',          [MessageController::class, 'messages']);
    Route::post('/conversations/{conversation}/messages',         [MessageController::class, 'send']);

    // ── Administration ────────────────────────────────────────────────────────
    Route::prefix('admin')->group(function () {
        Route::get('/stats',                              [AdminController::class, 'stats']);
        Route::get('/users',                              [AdminController::class, 'users']);
        Route::patch('/users/{user}/toggle',              [AdminController::class, 'toggleUser']);
        Route::get('/companies/pending',                  [AdminController::class, 'pendingCompanies']);
        Route::patch('/companies/{company}/validate',     [AdminController::class, 'validateCompany']);
    });
});
