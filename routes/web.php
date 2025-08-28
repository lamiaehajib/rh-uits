<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\FormationObjectifController;
use App\Http\Controllers\ImagePreuveController;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectObjectifController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SuivrePointageController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenteObjectifController;

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');
Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');

Route::get('/send-test-email', function () {
    $details = [
        'title' => 'Test Email from Laravel',
        'body' => 'This is a test email.'
    ];

    Mail::raw('This is a test email.', function ($message) {
        $message->to('test@example.com') // Replace with a valid email address
                ->subject('Test Email')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    });

    return 'Test email sent!';
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Route pour afficher le tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
   
    //Route::get('/dashboard', [FormationController::class, 'index'])->name('dashboard');
    // Routes pour la gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour la gestion des projets
    Route::get('/project', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');

    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');




    //Route::get('/users', [RegisteredUserController::class, 'index'])->name('users.index');

// Formulaire pour ajouter un nouvel utilisateur
//Route::get('/users/create', [RegisteredUserController::class, 'create'])->name('users.create');

// Enregistrer un nouvel utilisateur
//Route::post('/users', [RegisteredUserController::class, 'store'])->name('users.store');

// Afficher les détails d’un utilisateur spécifique
//Route::get('/users/{id}', [RegisteredUserController::class, 'show'])->name('users.show');

// Formulaire pour modifier un utilisateur spécifique
//Route::get('/users/{id}/edit', [RegisteredUserController::class, 'edit'])->name('users.edit');

// Mettre à jour un utilisateur spécifique
//Route::put('/users/{id}', [RegisteredUserController::class, 'update'])->name('users.update');

// Supprimer un utilisateur spécifique
//Route::delete('/users/{id}', [RegisteredUserController::class, 'destroy'])->name('users.destroy');

Route::get('/taches', [TacheController::class, 'index'])->name('taches.index');


Route::post('taches/{id}/duplicate', [TacheController::class, 'duplicate'])->name('taches.duplicate');
Route::patch('taches/{id}/complete', [TacheController::class, 'markAsComplete'])->name('taches.complete');
Route::get('taches/dashboard', [TacheController::class, 'dashboard'])->name('taches.dashboard');
Route::get('taches/export', [TacheController::class, 'export'])->name('taches.export');

Route::resource('taches', TacheController::class);

Route::resource('formations', FormationController::class);
 Route::post('formations/{formation}/duplicate', [FormationController::class, 'duplicate'])->name('formations.duplicate');
// Additional routes that are not part of the standard resource methods

// Route for downloading a file associated with a formation
Route::get('formations/{id}/download', [FormationController::class, 'downloadFile'])->name('formations.download');

// Route for getting formation statistics
Route::get('formations/stats', [FormationController::class, 'getStats'])->name('formations.stats');

// If you want to customize the names or only include specific routes from the resource:
/*
Route::resource('formations', FormationController::class)->only([
    'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
]);

// Or to exclude some:
Route::resource('formations', FormationController::class)->except([
    'create', 'store' // Example: if you only want to view/edit/delete, not create new ones via this route
]);
*/


Route::resource('vente_objectifs', VenteObjectifController::class);

    
   
    
    Route::get('/suivre-pointages', [SuivrePointageController::class, 'index'])->name('pointage.index');
Route::post('/suivre-pointages/pointer', [SuivrePointageController::class, 'pointer'])->name('pointage.pointer');
Route::get('/suivre-pointages/{id}', [SuivrePointageController::class, 'show'])->name('pointage.show');
Route::put('/suivre-pointages/{id}/corriger', [SuivrePointageController::class, 'corriger'])->name('suivre-pointages.corriger'); // ou POST selon le formulaire

// Route API (si statistiques est une requête AJAX)
Route::get('/suivre-pointages/statistiques', [SuivrePointageController::class, 'statistiques'])->name('suivre-pointages.statistiques');

// Routes réservées aux administrateurs
Route::middleware(['auth', 'role:Sup_Admin|Custom_Admin'])->group(function () {
    
    // Route pour exporter les pointages en CSV
    Route::get('/pointages/export/csv', [SuivrePointageController::class, 'exporter'])
        ->name('suivre_pointage.export');
    
    // Route pour corriger un pointage
    Route::put('/pointages/{id}/corriger', [SuivrePointageController::class, 'corriger'])
        ->name('suivre_pointage.corriger');
});
 // ✅ routes personnalisées أولاً
Route::get('/objectifs/calendar-view', [ObjectifController::class, 'calendarView'])->name('objectifs.calendar.view');
Route::get('/objectifs/calendar', [ObjectifController::class, 'calendar'])->name('objectifs.calendar');
Route::get('/objectifs/export', [ObjectifController::class, 'export'])->name('objectifs.export');
Route::get('/objectifs/all', [ObjectifController::class, 'getAllObjectifs'])->name('objectifs.all');
Route::post('objectifs/bulk-action', [ObjectifController::class, 'bulkAction'])->name('objectifs.bulkAction');
Route::post('objectifs/{objectif}/update-progress', [ObjectifController::class, 'updateProgress'])->name('objectifs.updateProgress');
Route::post('objectifs/{objectif}/duplicate', [ObjectifController::class, 'duplicate'])->name('objectifs.duplicate');
// ✅ خليه فالأخير
Route::resource('objectifs', ObjectifController::class);

   



 Route::resource('reclamations', ReclamationController::class);

    // Specific routes not covered by resource
    Route::post('reclamations/{reclamation}/update-status', [ReclamationController::class, 'updateStatus'])->name('reclamations.updateStatus');
    Route::get('reclamations/{reclamation}/download-attachment/{attachmentIndex}', [ReclamationController::class, 'downloadAttachment'])->name('reclamations.downloadAttachment');
    Route::get('reclamations/export', [ReclamationController::class, 'export'])->name('reclamations.export');
    Route::get('reclamations/dashboard', [ReclamationController::class, 'dashboard'])->name('reclamations.dashboard');
Route::resource('users', UserController::class);
// Dans routes/web.php
Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
Route::post('/users/{id}/duplicate', [UserController::class, 'duplicate'])->name('users.duplicate');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');


Route::resource('roles', RoleController::class);
Route::post('/suivre-pointage/pointer', [SuivrePointageController::class, 'pointer'])->name('suivre_pointage.pointer');

Route::middleware(['auth', 'check.clocked.in'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
       
    
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])
        ->name('dashboard.analytics');
    
    Route::post('/dashboard/export', [DashboardController::class, 'export'])
        ->name('dashboard.export');
});
Route::resource('image_preuve', ImagePreuveController::class);
Route::get('/image_preuve/{id}/download', [ImagePreuveController::class, 'download'])->name('image_preuve.download');
Route::get('/test-404', function () {
    abort(404);
});
Route::get('/login-history', [AuthenticatedSessionController::class, 'showLoginHistory'])->name('login.history');
});

// Handle registration

require __DIR__.'/auth.php';
