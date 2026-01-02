<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AvancementController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\DepensesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetController;
use App\Http\Controllers\RendezVousController;
use App\Http\Controllers\RetardCongeController;
use App\Http\Controllers\TwoFactorController;
use App\Models\Avancement;
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

use App\Models\Projet;
use App\Models\RendezVous;
use App\Models\Reclamation;
use App\Models\User;

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

Route::get('/2fa/notice', [TwoFactorController::class, 'showVerificationForm'])->name('verification.notice');
Route::post('/2fa/verify', [TwoFactorController::class, 'verifyCode'])->name('verification.verify');
Route::middleware(['auth', 'verified'])->group(function () {


    // Route pour afficher le tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
   Route::get('/download-backup', [BackupController::class, 'download'])->name('download.backup');
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


Route::get('/taches/corbeille', [TacheController::class, 'corbeille'])
      ->name('taches.corbeille');

// 2. Route dyal Restauration
Route::put('/taches/{id}/restore', [TacheController::class, 'restore'])
      ->name('taches.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/taches/{id}/forceDelete', [TacheController::class, 'forceDelete'])
      ->name('taches.forceDelete');
Route::get('/taches', [TacheController::class, 'index'])->name('taches.index');


Route::post('taches/{id}/duplicate', [TacheController::class, 'duplicate'])->name('taches.duplicate');
Route::patch('taches/{id}/complete', [TacheController::class, 'markAsComplete'])->name('taches.complete');
Route::get('taches/dashboard', [TacheController::class, 'dashboard'])->name('taches.dashboard');
Route::get('taches/export', [TacheController::class, 'export'])->name('taches.export');
Route::get('/taches/export-overdue', [TacheController::class, 'exportOverdueTasks'])
    ->name('taches.export.overdue');
      
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
Route::get('/suivre-pointages/{id}', [SuivrePointageController::class, 'show'])->name('pointage.show');
Route::put('/suivre-pointages/{id}/corriger', [SuivrePointageController::class, 'corriger'])->name('suivre-pointages.corriger'); // ou POST selon le formulaire

// Route API (si statistiques est une requête AJAX)
Route::get('/suivre-pointages/statistiques', [SuivrePointageController::class, 'statistiques'])->name('suivre-pointages.statistiques');

// Routes réservées aux administrateurs

    
    // Route pour exporter les pointages en CSV
    Route::get('/pointages/export/csv', [SuivrePointageController::class, 'exporter'])
        ->name('suivre_pointage.export');
    
    // Route pour corriger un pointage
    Route::put('/pointages/{id}/corriger', [SuivrePointageController::class, 'corriger'])
        ->name('suivre_pointage.corriger');

        Route::get('/pointages/export/excel', [SuivrePointageController::class, 'exporterExcel'])
    ->name('pointages.export.excel');

Route::get('/pointages/export/pdf', [SuivrePointageController::class, 'exporterPdf'])
    ->name('pointages.export.pdf');

// Route pour les données des charts
Route::get('/pointages/chart-data', [SuivrePointageController::class, 'getChartData'])
    ->name('pointages.chart.data');


    Route::post('/pointage/{id}/justificatif/soumettre', [SuivrePointageController::class, 'soumettreJustificatif'])
        ->name('pointage.justificatif.soumettre');
    
    Route::post('/pointage/{id}/justificatif/valider', [SuivrePointageController::class, 'validerJustificatif'])
        ->name('pointage.justificatif.valider');
    
    Route::get('/pointage/{id}/justificatif/telecharger', [SuivrePointageController::class, 'telechargerJustificatif'])
        ->name('pointage.justificatif.telecharger');

        Route::post('/admin/absences/detect-historical', function () {
        Artisan::call('absences:detect-historical');
        $output = Artisan::output();
        preg_match('/Absences enregistrées: (\d+)/', $output, $matches);
        return response()->json(['absences' => $matches[1] ?? 0]);
    })->name('admin.absences.historical');
    
    Route::post('/admin/absences/detect-daily', function () {
        Artisan::call('absences:daily');
        $output = Artisan::output();
        preg_match('/(\d+) absence/', $output, $matches);
        return response()->json(['absences' => $matches[1] ?? 0]);
    })->name('admin.absences.daily');



    // Justificatifs de retard
Route::post('/pointage/{id}/justificatif-retard/soumettre', [SuivrePointageController::class, 'soumettreJustificatifRetard'])
    ->name('pointage.justificatif.retard.soumettre');
Route::post('/pointage/{id}/justificatif-retard/valider', [SuivrePointageController::class, 'validerJustificatifRetard'])
    ->name('pointage.justificatif.retard.valider');
Route::get('/pointage/{id}/justificatif-retard/telecharger', [SuivrePointageController::class, 'telechargerJustificatifRetard'])
    ->name('pointage.justificatif.retard.telecharger');


    Route::get('/mes-retards', [RetardCongeController::class, 'monRapport'])
        ->name('retards.mon-rapport');
    
    // API - Vérifier alerte retard
    Route::get('/api/retards/check-alerte', [RetardCongeController::class, 'checkAlerte'])
        ->name('retards.check-alerte');
    
    // Admin uniquement
    
        
        // Dashboard admin des retards
        Route::get('/admin/retards/dashboard', [RetardCongeController::class, 'dashboardAdmin'])
            ->name('retards.dashboard-admin');
        
        // Exécuter les déductions manuellement
        Route::post('/admin/retards/executer-deductions', [RetardCongeController::class, 'executerDeductions'])
            ->name('retards.executer-deductions');
  
    
 // ✅ routes personnalisées أولاً
Route::get('/objectifs/corbeille', [ObjectifController::class, 'corbeille'])
      ->name('objectifs.corbeille');

// 2. استعادة الهدف
Route::put('/objectifs/{id}/restore', [ObjectifController::class, 'restore'])
      ->name('objectifs.restore');

// 3. الحذف النهائي
Route::delete('/objectifs/{id}/forceDelete', [ObjectifController::class, 'forceDelete'])
      ->name('objectifs.forceDelete');

 Route::get('/objectifs/calendar-view', [ObjectifController::class, 'calendarView'])->name('objectifs.calendar.view');
Route::get('/objectifs/calendar', [ObjectifController::class, 'calendar'])->name('objectifs.calendar');
Route::get('/objectifs/export', [ObjectifController::class, 'export'])->name('objectifs.export');
Route::get('/objectifs/all', [ObjectifController::class, 'getAllObjectifs'])->name('objectifs.all');
Route::post('objectifs/bulk-action', [ObjectifController::class, 'bulkAction'])->name('objectifs.bulkAction');
Route::post('objectifs/{objectif}/update-progress', [ObjectifController::class, 'updateProgress'])->name('objectifs.updateProgress');
Route::post('objectifs/{objectif}/duplicate', [ObjectifController::class, 'duplicate'])->name('objectifs.duplicate');
// ✅ خليه فالأخير
Route::resource('objectifs', ObjectifController::class);

   

Route::get('/reclamations/corbeille', [ReclamationController::class, 'corbeille'])
      ->name('reclamations.corbeille');

// 2. Route dyal Restauration
Route::put('/reclamations/{id}/restore', [ReclamationController::class, 'restore'])
      ->name('reclamations.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/reclamations/{id}/forceDelete', [ReclamationController::class, 'forceDelete'])
      ->name('reclamations.forceDelete');

     

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
       
    
    
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart.data');
    Route::get('/dashboard/export', [DashboardController::class, 'exportStats'])->name('dashboard.export');
});
Route::resource('image_preuve', ImagePreuveController::class);
Route::get('/image_preuve/{id}/download', [ImagePreuveController::class, 'download'])->name('image_preuve.download');
Route::get('/test-404', function () {
    abort(404);
});
Route::get('/login-history', [AuthenticatedSessionController::class, 'showLoginHistory'])->name('login.history');

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Projets

   
Route::get('/projets/corbeille', [ProjetController::class, 'corbeille'])
      ->name('projets.corbeille');

// 2. Route dyal Restauration
Route::put('/projets/{id}/restore', [ProjetController::class, 'restore'])
      ->name('projets.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/projets/{id}/forceDelete', [ProjetController::class, 'forceDelete'])
      ->name('projets.forceDelete');

    Route::resource('projets', ProjetController::class);
    Route::get('projets/{projet}/download', [ProjetController::class, 'downloadFile'])
    ->name('projets.download');
    // Rendez-vous
    Route::get('/rendez-vous/corbeille', [RendezVousController::class, 'corbeille'])
      ->name('rendezvous.corbeille');

// 2. Route dyal Restauration
Route::put('/rendez-vous/{id}/restore', [RendezVousController::class, 'restore'])
      ->name('rendezvous.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/rendez-vous/{id}/forceDelete', [RendezVousController::class, 'forceDelete'])
      ->name('rendezvous.forceDelete');
    Route::resource('rendez-vous', RendezVousController::class);
    Route::get('rendez-vous-aujourdhui', [RendezVousController::class, 'aujourdhui'])->name('rendez-vous.aujourdhui');
    Route::get('planning-semaine', [RendezVousController::class, 'planning'])->name('rendez-vous.planning');
    

    Route::get('/avancements/corbeille', [AvancementController::class, 'corbeille'])
         ->name('avancements.corbeille_globale'); 

         Route::put('/avancements/{id}/restore', [AvancementController::class, 'restore'])
      ->name('avancements.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/avancements/{id}/forceDelete', [AvancementController::class, 'forceDelete'])
      ->name('avancements.forceDelete');
    // Avancements (imbriqué dans les projets)
    Route::prefix('projets/{projet}')->group(function () {
       


      

        Route::resource('avancements', AvancementController::class);
        Route::patch('avancements/{avancement}/pourcentage', [AvancementController::class, 'updatePourcentage'])
            ->name('avancements.update-pourcentage');
    });

    

    
});

// Routes pour les clients (optionnel)
Route::prefix('client')->name('client.')->middleware('auth')->group(function () {
    // Dashboard du client
    Route::get('dashboard', function () {
        $user = auth()->user();

        // Récupérer TOUS les projets de l'utilisateur en une seule requête
        $projets = $user->projets()->get();
        // Récupérer la liste des IDs de ces projets
        $projetIds = $projets->pluck('id');

        // Utiliser les collections pour compter et filtrer
        $totalProjets = $projets->count();
        $projetsEnCours = $projets->where('statut_projet', 'en cours')->count();
        $projetsTermines = $projets->where('statut_projet', 'terminé')->count();
        $projetsEnAttente = $projets->where('statut_projet', 'en attente')->count();
        $projetsAnnules = $projets->where('statut_projet', 'annulé')->count();

        $projetsRecents = $projets->sortByDesc('created_at')->take(5);

        
        $chartData = [
            'labels' => ['En cours', 'Terminés', 'En attente', 'Annulés'],
            'data' => [$projetsEnCours, $projetsTermines, $projetsEnAttente, $projetsAnnules]
        ];

      
        // On récupère les rendez-vous en se basant sur les IDs des projets
        $rendezVous = RendezVous::whereIn('projet_id', $projetIds)
                               ->where('date_heure', '>', now())
                               ->orderBy('date_heure', 'asc')
                               ->take(5)->get();

        $reclamations = Reclamation::where('iduser', $user->id)
                                   ->where('status', '!=', 'resolved')
                                   ->latest()
                                   ->take(5)->get();
        
        return view('client.dashboard', compact(
            'user',
            'totalProjets',
            'projetsEnCours',
            'projetsTermines',
            'projetsRecents',
            'rendezVous',
            'reclamations',
            'projetsEnAttente',
            'projetsAnnules',
            'chartData'
        ));
    })->name('dashboard');

    // Liste des projets
    Route::get('mes-projets', function () {
        $projets = auth()->user()->projets()->with('avancements')->get();
        return view('client.projets.index', compact('projets'));
    })->name('projets.index');
    
    // Détails d'un projet
    Route::get('projet/{projet}', function (Projet $projet) {
        // if ($projet->user_id !== auth()->id()) {
        //     abort(403);
        // }
        $projet->load(['avancements', 'rendezVous']);
        $pourcentageGlobal = $projet->avancements->sum('pourcentage');

    // S'assurer que le pourcentage ne dépasse pas 100%
    if ($pourcentageGlobal > 100) {
        $pourcentageGlobal = 100;
    }
        return view('client.projets.show', compact('projet', 'pourcentageGlobal'));
    })->name('projets.show');


    Route::get('avancement/{avancement}', function (Avancement $avancement) {
      
        return view('client.avancements.show', compact('avancement'));
    })->name('avancements.show');


 Route::post('/avancements/{avancement}/comment', [AvancementController::class, 'addCommentByClient'])
        ->name('client.avancements.addComment');

        

    // Route pour télécharger le fichier d'un avancement
Route::get('avancement/{avancement}/download', [AvancementController::class, 'downloadFile'])
    ->name('avancements.download');



    // Liste des rendez-vous
    Route::get('rendez-vous', function () {
        $rendezVous = RendezVous::where('user_id', auth()->id())
            ->with('projet')
            ->orderBy('date_heure', 'asc')
            ->paginate(10);
        return view('admin.rendez-vous.index', compact('rendezVous'));
    })->name('rendez-vous.index');



    // Liste des réclamations
    Route::get('reclamations', function () {
        // Fixe: Utilisation de 'iduser' au lieu de 'user_id'
        $reclamations = Reclamation::where('iduser', auth()->id())
            ->latest()
            ->paginate(10);
        return view('reclamations.index', compact('reclamations'));
    })->name('reclamations.index');

    Route::get('/client/planning/{periode?}', [RendezVousController::class, 'clientPlanning'])
    ->name('client.planning');

    Route::put('/rendez-vous/{rendezVous}/cancel', [RendezVousController::class, 'cancelRendezVous'])
    ->name('client.rendez-vous.cancel');

    Route::get('/rendez-vous/{rendezVous}/reprogrammer', [RendezVousController::class, 'reprogrammer'])->name('client.rendez-vous.reprogrammer');
Route::put('/rendez-vous/{rendezVous}/reprogram-store', [RendezVousController::class, 'reprogramStore'])->name('client.rendez-vous.reprogram-store');


Route::put('/rendez-vous/{rendezVous}/confirm', [RendezVousController::class, 'confirmRendezVous'])
    ->name('client.rendez-vous.confirm');

    Route::get('/planning/historique', [RendezVousController::class, 'historiqueClient'])
    ->name('client.planning.historique');

    Route::get('/rendez-vous/{rendezVous}', [RendezVousController::class, 'showClient'])
    ->name('client.rendez-vous.show');
});


        Route::get('/conges', [CongeController::class, 'index'])->name('conges.index');
        Route::get('/conges/create', [CongeController::class, 'create'])->name('conges.create');
        Route::post('/conges', [CongeController::class, 'store'])->name('conges.store');
        Route::get('/conges/solde', [CongeController::class, 'solde'])->name('conges.solde');
        Route::post('/conges/preview', [CongeController::class, 'previewCalcul'])->name('conges.preview');
    
    
    // Routes pour voir les détails (tous sauf Clients)
    Route::get('/conges/{conge}', [CongeController::class, 'show'])
        ->name('conges.show');    
    // Routes réservées aux admins (Custom_Admin et Sup_Admin)
        Route::post('/conges/{conge}/approve', [CongeController::class, 'approve'])->name('conges.approve');
        Route::post('/conges/{conge}/reject', [CongeController::class, 'reject'])->name('conges.reject');
    


        Route::get('/depenses', [DepensesController::class, 'index'])->name('depenses.index');
    
    // Dépenses Fixes
    // Dans routes/web.php, ajoute cette route:
Route::post('/depenses/fixes/generer-salaires', [DepensesController::class, 'genererSalaires'])
    ->name('depenses.fixes.generer-salaires');
    Route::prefix('depenses/fixes')->name('depenses.fixes.')->group(function () {
        Route::get('/', [DepensesController::class, 'depensesFixes'])->name('index');
        Route::post('/', [DepensesController::class, 'storeDepenseFixe'])->name('store');
        Route::put('/{depense}', [DepensesController::class, 'updateDepenseFixe'])->name('update');
        Route::delete('/{depense}', [DepensesController::class, 'destroyDepenseFixe'])->name('destroy');
        
    });
    
    // Dépenses Variables
    Route::prefix('depenses/variables')->name('depenses.variables.')->group(function () {
        Route::get('/', [DepensesController::class, 'depensesVariables'])->name('index');
        Route::post('/', [DepensesController::class, 'storeDepenseVariable'])->name('store');
        Route::put('/{depense}', [DepensesController::class, 'updateDepenseVariable'])->name('update');
        Route::delete('/{depense}', [DepensesController::class, 'destroyDepenseVariable'])->name('destroy');
    });
    
    // Rapport mensuel
    Route::get('/depenses/rapport', [DepensesController::class, 'rapportMensuel'])->name('depenses.rapport');
});

// Handle registration

require __DIR__.'/auth.php';
