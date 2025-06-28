<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuivrePointage; // S'assurer que ce modèle est correctement importé
use Carbon\Carbon;

class CheckUserClockIn
{
    /**
     * Gère une requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Si l'utilisateur n'est pas connecté, laisser le middleware d'authentification gérer cela.
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Les administrateurs et les rôles spécifiques (comme Custom_Admin) n'ont pas besoin de pointer.
        // Les laisser passer directement.
        if ($user->hasRole('Sup_Admin') || $user->hasRole('Custom_Admin')) {
            return $next($request);
        }

        // SI la requête actuelle est pour le tableau de bord, la page d'index de pointage,
        // ou la route de soumission du pointage, laisser passer l'utilisateur immédiatement.
        // Cela permet aux utilisateurs non-pointés d'accéder au tableau de bord pour pointer.
        if ($request->routeIs('dashboard') ||
            $request->routeIs('pointage.index') ||
            $request->routeIs('pointage.pointer')) {
            return $next($request);
        }

        // Vérifier si l'utilisateur a pointé son arrivée aujourd'hui.
        $hasClockedInToday = SuivrePointage::where('iduser', $user->id)
            ->whereDate('heure_arrivee', Carbon::today())
            ->exists();

        // Si l'utilisateur n'a PAS pointé son arrivée aujourd'hui,
        // le rediriger vers la page d'index de pointage avec un message.
        // Toutes les autres pages (non dashboard, non pointage.index/pointer) nécessiteront le pointage.
        if (!$hasClockedInToday) {
            return redirect()->route('pointage.index')->with('info', 'Veuillez pointer votre arrivée pour accéder à cette page.');
        }

        // Si toutes les conditions ci-dessus sont remplies (connecté, non admin, non sur une page d'exception, OU déjà pointé),
        // alors laisser la requête passer.
        return $next($request);
    }
}
