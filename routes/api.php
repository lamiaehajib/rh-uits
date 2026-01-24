<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

    Route::prefix('salaires')->group(function () {
    
    // ✅ 1. Total des salaires
    Route::get('/total', function() {
        $users = User::where('is_active', true)->get(['salaire']);
        
        return response()->json([
            'success' => true,
            'total' => $users->sum('salaire'),
            'count' => $users->count(),
            'moyenne' => $users->avg('salaire'),
        ]);
    });

    // ✅ 2. Salaires du mois actuel (détaillés)
    Route::get('/mois-actuel', function() {
        $users = User::select('id', 'name', 'poste', 'salaire', 'email')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'mois' => now()->month,
            'annee' => now()->year,
            'salaires' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'nom' => $user->name,
                    'poste' => $user->poste,
                    'salaire' => (float) $user->salaire,
                ];
            }),
            'total' => $users->sum('salaire'),
            'count' => $users->count(),
        ]);
    });

    // ✅ 3. Salaires par poste
    Route::get('/par-poste', function() {
        $data = User::where('is_active', true)
            ->groupBy('poste')
            ->selectRaw('poste, COUNT(*) as nombre, SUM(salaire) as total_salaires, AVG(salaire) as moyenne')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    });
});

// ✅ 4. Liste des employés
Route::get('/employees', function() {
    $users = User::select('id', 'name', 'poste', 'salaire', 'email', 'tele')
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
    
    return response()->json([
        'success' => true,
        'employees' => $users,
        'count' => $users->count(),
    ]);
});

// ✅ 5. Détails d'un employé
Route::get('/employees/{id}', function($id) {
    $user = User::select('id', 'name', 'poste', 'salaire', 'email', 'tele', 'adresse')
        ->where('is_active', true)
        ->findOrFail($id);
    
    return response()->json([
        'success' => true,
        'employee' => $user,
    ]);
});
});



