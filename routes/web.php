<?php

use App\Http\Controllers\Auth\ConnexionController;
use App\Http\Controllers\Auth\InscriptionController;
use App\Http\Controllers\Entreprise\CandidatureController as EntrepriseCandidatureController;
use App\Http\Controllers\Entreprise\OffreController as EntrepriseOffreController;
use App\Http\Controllers\Stagiaire\CandidatureController as StagiaireCandidatureController;
use App\Http\Controllers\Stagiaire\OffreController as StagiaireOffreController;
use App\Http\Controllers\Stagiaire\StageController;
use Illuminate\Support\Facades\Route;

// ── Page d'accueil ────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('accueil');

// ── Authentification ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/connexion',  [ConnexionController::class, 'index'])->name('login');
    Route::post('/connexion', [ConnexionController::class, 'connecter'])->name('login.store');

    Route::get('/inscription',  [InscriptionController::class, 'index'])->name('register');
    Route::post('/inscription', [InscriptionController::class, 'inscrire'])->name('register.store');
});

Route::post('/deconnexion', [ConnexionController::class, 'deconnecter'])
    ->middleware('auth')
    ->name('logout');

// ── Offres publiques (sans connexion) ─────────────────────────
Route::get('/offres',         [StagiaireOffreController::class, 'index'])->name('offres.index');
Route::get('/offres/{offre}', [StagiaireOffreController::class, 'show'])->name('offres.show');

// ── Espace Stagiaire ──────────────────────────────────────────
Route::middleware(['auth', 'role:stagiaire'])
    ->prefix('stagiaire')
    ->name('stagiaire.')
    ->group(function () {

        Route::get('/candidatures',                  [StagiaireCandidatureController::class, 'index'])->name('candidatures.index');
        Route::get('/offres/{offre}/postuler',       [StagiaireCandidatureController::class, 'create'])->name('candidatures.create');
        Route::post('/offres/{offre}/postuler',      [StagiaireCandidatureController::class, 'store'])->name('candidatures.store');
        Route::delete('/candidatures/{candidature}', [StagiaireCandidatureController::class, 'retirer'])->name('candidatures.retirer');

        Route::get('/stages', [StageController::class, 'index'])->name('stages.index');
    });

// ── Espace Entreprise ─────────────────────────────────────────
Route::middleware(['auth', 'role:entreprise'])
    ->prefix('entreprise')
    ->name('entreprise.')
    ->group(function () {

        Route::get('/offres/nouvelle',     [EntrepriseOffreController::class, 'create'])->name('offres.create');
        Route::get('/offres',              [EntrepriseOffreController::class, 'index'])->name('offres.index');
        Route::post('/offres',             [EntrepriseOffreController::class, 'store'])->name('offres.store');
        Route::get('/offres/{offre}',      [EntrepriseOffreController::class, 'show'])->name('offres.show');
        Route::get('/offres/{offre}/edit', [EntrepriseOffreController::class, 'edit'])->name('offres.edit');
        Route::put('/offres/{offre}',      [EntrepriseOffreController::class, 'update'])->name('offres.update');
        Route::delete('/offres/{offre}',   [EntrepriseOffreController::class, 'destroy'])->name('offres.destroy');

        Route::get('/offres/{offre}/candidatures',      [EntrepriseCandidatureController::class, 'index'])->name('candidatures.index');
        Route::get('/candidatures/{candidature}',       [EntrepriseCandidatureController::class, 'show'])->name('candidatures.show');
        Route::post('/candidatures/{candidature}/accepter', [EntrepriseCandidatureController::class, 'accepter'])->name('candidatures.accepter');
        Route::post('/candidatures/{candidature}/refuser',  [EntrepriseCandidatureController::class, 'refuser'])->name('candidatures.refuser');
    });

// ── Dashboard (redirige selon le rôle) 
Route::get('/dashboard', function () {
    return match (auth()->user()->role) {
        'stagiaire'  => redirect()->route('stagiaire.candidatures.index'),
        'entreprise' => redirect()->route('entreprise.offres.index'),
        'admin'      => redirect()->route('admin.dashboard'),
        'encadrant'  => redirect()->route('encadrant.stages.index'),
        default      => redirect()->route('accueil'),
    };
})->middleware('auth')->name('dashboard');

// ── Espace Admin 
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard',  [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::get('/utilisateurs', [\App\Http\Controllers\Admin\DashboardController::class, 'utilisateurs'])->name('utilisateurs');
        Route::post('/utilisateurs/{user}/toggle', [\App\Http\Controllers\Admin\DashboardController::class, 'toggleUtilisateur'])->name('utilisateurs.toggle');

        Route::get('/entreprises', [\App\Http\Controllers\Admin\DashboardController::class, 'entreprises'])->name('entreprises');
        Route::post('/entreprises/{entreprise}/valider', [\App\Http\Controllers\Admin\DashboardController::class, 'validerEntreprise'])->name('entreprises.valider');
        Route::post('/entreprises/{entreprise}/rejeter', [\App\Http\Controllers\Admin\DashboardController::class, 'rejeterEntreprise'])->name('entreprises.rejeter');

        Route::get('/stages', [\App\Http\Controllers\Admin\DashboardController::class, 'stages'])->name('stages');
    });
// ── Espace Encadrant 
Route::middleware(['auth', 'role:encadrant'])
    ->prefix('encadrant')
    ->name('encadrant.')
    ->group(function () {
        Route::get('/stages',                          [\App\Http\Controllers\Encadrant\StageController::class, 'index'])->name('stages.index');
        Route::get('/stages/{stage}',                  [\App\Http\Controllers\Encadrant\StageController::class, 'show'])->name('stages.show');
        Route::post('/stages/{stage}/compte-rendu',    [\App\Http\Controllers\Encadrant\StageController::class, 'compteRendu'])->name('stages.compte-rendu');
    });

Route::post('/stages/{stage}/assigner-encadrant', 
    [\App\Http\Controllers\Admin\DashboardController::class, 'assignerEncadrant']
)->name('stages.assigner-encadrant');

// Notifications
Route::post('/notifications/marquer-lues', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->middleware('auth')->name('notifications.marquer-lues');


//  API JSON 
Route::prefix('api')->name('api.')->group(function () {


// Offres publiques
Route::get('/offres', [\App\Http\Controllers\Api\OffreApiController::class, 'index'])->name('offres.index');
Route::get('/offres/{id}',   [\App\Http\Controllers\Api\OffreApiController::class, 'show'])->name('offres.show');

Route::middleware('auth')->group(function () {
    Route::get('/stagiaire/candidatures', [\App\Http\Controllers\Api\CandidatureApiController::class, 'mesCandidatures'])->name('stagiaire.candidatures');
    Route::get('/stagiaire/stages',       [\App\Http\Controllers\Api\CandidatureApiController::class, 'mesStages'])->name('stagiaire.stages');
    Route::get('/entreprise/candidatures',[\App\Http\Controllers\Api\CandidatureApiController::class, 'candidaturesEntreprise'])->name('entreprise.candidatures');
});
});
