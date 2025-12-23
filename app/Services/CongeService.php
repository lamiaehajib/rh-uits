<?php

namespace App\Services;

use App\Models\JourFerie;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CongeService
{
    /**
     * Calculer le nombre de jours calendaires entre deux dates
     * TOUS les jours sont comptés (repos + fériés + ouvrables)
     */
    public function calculerJoursOuvrables($dateDebut, $dateFin, User $user)
    {
        $debut = Carbon::parse($dateDebut);
        $fin = Carbon::parse($dateFin);
        
        // Calcul simple : nombre de jours entre les deux dates (inclus)
        $joursTotal = $debut->diffInDays($fin) + 1;
        
        return $joursTotal;
    }
    
    /**
     * Vérifier si un utilisateur peut prendre un congé
     */
    public function peutPrendreConge(User $user, $nombreJours, $annee)
    {
        $solde = \App\Models\SoldeConge::initSolde($user->id, $annee);
        return $solde->jours_restants >= $nombreJours;
    }
    
    /**
     * Récupérer le détail des jours pour une période de congé
     * Version simplifiée : on affiche juste les jours avec leur type
     */
    public function getDetailJours($dateDebut, $dateFin, User $user)
    {
        $debut = Carbon::parse($dateDebut);
        $fin = Carbon::parse($dateFin);
        
        // Récupérer les jours de repos pour l'affichage
        $joursRepos = $this->getJoursRepos($user);
        $joursReposCarbon = $this->convertJoursReposToCarbon($joursRepos);
        
        // Récupérer les jours fériés pour l'affichage
        $annee = $debut->year;
        $joursFeries = JourFerie::getForYear($annee);
        if ($fin->year > $debut->year) {
            $joursFeries = array_merge($joursFeries, JourFerie::getForYear($fin->year));
        }
        
        $details = [
            'total' => 0,
            'ouvrables' => 0,
            'repos' => 0,
            'feries' => 0,
            'jours' => []
        ];
        
        $period = CarbonPeriod::create($debut, $fin);
        
        foreach ($period as $date) {
            $details['total']++;
            
            $type = 'ouvrable';
            $dayOfWeek = $date->dayOfWeek;
            
            // Identifier le type de jour (pour l'affichage uniquement)
            if (in_array($dayOfWeek, $joursReposCarbon)) {
                $type = 'repos';
                $details['repos']++;
            } else {
                $estFerie = false;
                foreach ($joursFeries as $jourFerie) {
                    if ($date->isSameDay(Carbon::parse($jourFerie))) {
                        $estFerie = true;
                        break;
                    }
                }
                
                if ($estFerie) {
                    $type = 'ferie';
                    $details['feries']++;
                } else {
                    $details['ouvrables']++;
                }
            }
            
            $details['jours'][] = [
                'date' => $date->format('Y-m-d'),
                'jour' => $date->locale('fr')->isoFormat('dddd'),
                'type' => $type
            ];
        }
        
        return $details;
    }
    
    /**
     * Récupérer les jours de repos de l'utilisateur
     */
    private function getJoursRepos(User $user)
    {
        if (empty($user->repos)) {
            return [];
        }
        
        if (is_array($user->repos)) {
            return $user->repos;
        }
        
        if (is_string($user->repos)) {
            // Format "Lundi,Dimanche"
            if (strpos($user->repos, ',') !== false) {
                return array_map('trim', explode(',', $user->repos));
            }
            
            // Format JSON
            $decoded = json_decode($user->repos, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            
            // Un seul jour
            return [trim($user->repos)];
        }
        
        return [];
    }
    
    /**
     * Convertir les jours de repos en format Carbon
     */
    private function convertJoursReposToCarbon($joursRepos)
    {
        $mapping = [
            'lundi' => Carbon::MONDAY,
            'mardi' => Carbon::TUESDAY,
            'mercredi' => Carbon::WEDNESDAY,
            'jeudi' => Carbon::THURSDAY,
            'vendredi' => Carbon::FRIDAY,
            'samedi' => Carbon::SATURDAY,
            'dimanche' => Carbon::SUNDAY,
        ];
        
        $carbonDays = [];
        foreach ($joursRepos as $jour) {
            $jourNormalized = trim(mb_strtolower($jour));
            $jourNormalized = $this->removeAccents($jourNormalized);
            
            if (isset($mapping[$jourNormalized])) {
                $carbonDays[] = $mapping[$jourNormalized];
            }
        }
        
        return $carbonDays;
    }
    
    /**
     * Enlever les accents
     */
    private function removeAccents($string)
    {
        $unwanted_array = [
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'à' => 'a', 'â' => 'a', 'ä' => 'a',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ö' => 'o',
            'ç' => 'c'
        ];
        
        return strtr($string, $unwanted_array);
    }
}