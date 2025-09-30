<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    /**
     * Affiche le formulaire de saisie du code de vérification.
     */
    public function showVerificationForm(Request $request)
    {
        // 1. Récupère l'ID utilisateur stocké temporairement en session
        $userId = $request->session()->get('2fa_user_id');
        
        // S'il n'y a pas d'ID en session, redirige vers la page de connexion
        if (!$userId) {
            return redirect('/login');
        }
        
        // 2. Récupère les données de l'utilisateur depuis la base de données
        $user = User::find($userId);

        // Si l'utilisateur n'est pas trouvé
        if (!$user) {
            return redirect('/login')->withErrors(['error' => 'Impossible d\'identifier l\'utilisateur, veuillez vous reconnecter.']);
        }
        
        // 3. Affiche la page de saisie du code
        // Nous passons l'objet Utilisateur (User object) à la vue pour afficher des informations (comme les quatre derniers chiffres du téléphone)
        return view('auth.two-factor', compact('user'));
    }

    /**
     * Vérifie le code d'authentification saisi par l'utilisateur.
     */
    public function verifyCode(Request $request)
    {
        // 1. Valide l'entrée (doit être numérique et obligatoire)
        $request->validate([
            'two_factor_code' => 'required|numeric',
        ], [
            'two_factor_code.required' => 'Le code de vérification est requis.',
            'two_factor_code.numeric' => 'Le code de vérification doit être numérique.',
        ]);

        // 2. Récupère l'ID utilisateur de la session (sans le supprimer tout de suite pour permettre une nouvelle tentative)
        $userId = $request->session()->get('2fa_user_id');
        $user = User::find($userId); 

        // Vérifie l'existence de l'utilisateur
        if (!$user) {
            return redirect('/login')->withErrors(['error' => 'La session a expiré. Veuillez vous reconnecter.']);
        }

        // 3. Vérifie la correspondance du code et sa validité temporelle
        if ($request->two_factor_code == $user->code && now()->lessThan($user->two_factor_expires_at)) {
            
            // 4. Vérification réussie : connecte complètement l'utilisateur
            Auth::login($user); 
            
            // 5. Supprime l'ID de la session
            $request->session()->forget('2fa_user_id');
            
            // 6. Supprime le code temporaire de la base de données
            $user->update(['code' => null, 'two_factor_expires_at' => null]);

            // 7. Redirige vers la destination prévue (Dashboard)
            return redirect()->intended('/'); 
        }

        // 8. Échec de la vérification (Code incorrect ou expiré)
        throw ValidationException::withMessages([
            'two_factor_code' => ['Le code de vérification est incorrect ou a expiré.']
        ]);
    }
}
