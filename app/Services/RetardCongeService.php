<?php

namespace App\Services;

use App\Models\SuivrePointage;
use App\Models\SoldeConge;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetardCongeService
{
    const HEURE_LIMITE = '09:10:00'; // Heure limite d'arrivée
    const SEUIL_RETARD_DEDUCTION = 30; // 30 minutes de retard = déduction d'un jour
    const ALERTE_RETARD = 15; // Alerte à 15 minutes

    /**
     * Calculer le cumul des retards non justifiés pour un utilisateur sur un mois
     */
    public function calculerRetardMensuel($userId, $mois = null, $annee = null)
    {
        $mois = $mois ?? Carbon::now()->month;
        $annee = $annee ?? Carbon::now()->year;

        // Récupérer tous les retards non justifiés du mois
        $retards = SuivrePointage::where('iduser', $userId)
            ->where('type', 'presence')
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', [self::HEURE_LIMITE])
            ->whereMonth('date_pointage', $mois)
            ->whereYear('date_pointage', $annee)
            ->where(function($query) {
                $query->whereNull('justificatif_retard')
                      ->orWhere('retard_justifie', false);
            })
            ->get();

        $totalMinutesRetard = 0;
        foreach ($retards as $retard) {
            $totalMinutesRetard += $retard->getRetardMinutes();
        }

        return [
            'total_minutes' => $totalMinutesRetard,
            'nombre_retards' => $retards->count(),
            'jours_a_deduire' => floor($totalMinutesRetard / self::SEUIL_RETARD_DEDUCTION)
        ];
    }

    /**
     * Vérifier si un utilisateur doit être alerté pour ses retards
     */
    public function verifierAlerteRetard($userId)
    {
        $statsRetard = $this->calculerRetardMensuel($userId);
        
        if ($statsRetard['total_minutes'] >= self::ALERTE_RETARD) {
            return [
                'doit_alerter' => true,
                'message' => "Attention ! Vous avez cumulé {$statsRetard['total_minutes']} minutes de retard ce mois-ci. " .
                            "À partir de " . self::SEUIL_RETARD_DEDUCTION . " minutes, un jour de congé sera déduit.",
                'minutes_restantes' => self::SEUIL_RETARD_DEDUCTION - ($statsRetard['total_minutes'] % self::SEUIL_RETARD_DEDUCTION),
                'jours_potentiels' => $statsRetard['jours_a_deduire']
            ];
        }

        return ['doit_alerter' => false];
    }

    /**
     * Traiter les déductions de congés en fin de mois
     * Cette méthode devrait être appelée par une commande planifiée (cron)
     */
    public function traiterDeductionsMensuelles()
    {
        $moisPrecedent = Carbon::now()->subMonth();
        $mois = $moisPrecedent->month;
        $annee = $moisPrecedent->year;

        // Récupérer tous les utilisateurs ayant des pointages
$utilisateurs = User::whereHas('suiviPointages', function($query) use ($mois, $annee) {
                $query->whereMonth('date_pointage', $mois)
                  ->whereYear('date_pointage', $annee);
        })->get();

        $deductions = [];

        DB::beginTransaction();
        try {
            foreach ($utilisateurs as $user) {
                $statsRetard = $this->calculerRetardMensuel($user->id, $mois, $annee);
                
                if ($statsRetard['jours_a_deduire'] > 0) {
                    // Déduire les jours de congé
                    $solde = SoldeConge::initSolde($user->id, $annee);
                    
                    if ($solde->jours_restants >= $statsRetard['jours_a_deduire']) {
                        $solde->utiliserJours($statsRetard['jours_a_deduire']);
                        
                        $deductions[] = [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'jours_deduits' => $statsRetard['jours_a_deduire'],
                            'minutes_retard' => $statsRetard['total_minutes'],
                            'nouveau_solde' => $solde->jours_restants
                        ];

                        Log::info('Déduction congé pour retards', [
                            'user_id' => $user->id,
                            'mois' => $mois,
                            'annee' => $annee,
                            'jours_deduits' => $statsRetard['jours_a_deduire'],
                            'minutes_retard' => $statsRetard['total_minutes']
                        ]);
                    } else {
                        Log::warning('Solde insuffisant pour déduction', [
                            'user_id' => $user->id,
                            'jours_necessaires' => $statsRetard['jours_a_deduire'],
                            'jours_disponibles' => $solde->jours_restants
                        ]);
                    }
                }
            }
            
            DB::commit();
            return ['success' => true, 'deductions' => $deductions];
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors des déductions mensuelles', [
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtenir un rapport des retards pour un utilisateur
     */
    public function rapportRetardsUtilisateur($userId, $mois = null, $annee = null)
    {
        $mois = $mois ?? Carbon::now()->month;
        $annee = $annee ?? Carbon::now()->year;

        $retards = SuivrePointage::where('iduser', $userId)
            ->where('type', 'presence')
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', [self::HEURE_LIMITE])
            ->whereMonth('date_pointage', $mois)
            ->whereYear('date_pointage', $annee)
            ->orderBy('date_pointage', 'desc')
            ->get();

        $statsRetard = $this->calculerRetardMensuel($userId, $mois, $annee);
        $solde = SoldeConge::initSolde($userId, $annee);

        return [
            'stats' => $statsRetard,
            'retards_details' => $retards->map(function($retard) {
                return [
                    'date' => $retard->date_pointage->format('d/m/Y'),
                    'heure_arrivee' => Carbon::parse($retard->heure_arrivee)->format('H:i'),
                    'minutes_retard' => $retard->getRetardMinutes(),
                    'justifie' => $retard->retard_justifie,
                    'statut_justificatif' => $retard->getJustificatifRetardStatus()
                ];
            }),
            'solde_conge' => [
                'total' => $solde->total_jours,
                'utilises' => $solde->jours_utilises,
                'restants' => $solde->jours_restants
            ],
            'alerte' => $this->verifierAlerteRetard($userId)
        ];
    }

    /**
     * Marquer un retard comme nécessitant une validation admin
     */
    public function marquerRetardPourValidation($pointageId)
    {
        $pointage = SuivrePointage::findOrFail($pointageId);
        
        if (!$pointage->isLate() || $pointage->hasJustificatifRetard()) {
            return false;
        }

        // Notifier l'admin (vous pouvez implémenter une notification Laravel ici)
        Log::info('Retard nécessitant validation admin', [
            'pointage_id' => $pointageId,
            'user_id' => $pointage->iduser,
            'minutes_retard' => $pointage->getRetardMinutes()
        ]);

        return true;
    }

    /**
     * Obtenir tous les retards en attente de validation admin
     */
    public function getRetardsEnAttenteValidation()
    {
        return SuivrePointage::where('type', 'presence')
            ->whereNotNull('heure_arrivee')
            ->whereRaw('TIME(heure_arrivee) > ?', [self::HEURE_LIMITE])
            ->whereNotNull('justificatif_retard')
            ->where('retard_justifie', false)
            ->with('user')
            ->orderBy('date_pointage', 'desc')
            ->get();
    }
}