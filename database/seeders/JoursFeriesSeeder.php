<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JourFerie;

class JoursFeriesSeeder extends Seeder
{
    public function run()
    {
        $joursFeries = [
            // 2026 - Jours fixes
            ['nom' => 'Nouvel An', 'date' => '2026-01-01', 'annee' => 2026, 'type' => 'fixe'],
            ['nom' => 'Manifeste de l\'Indépendance', 'date' => '2026-01-11', 'annee' => 2026, 'type' => 'fixe'],
            ['nom' => 'Fête du Travail', 'date' => '2026-05-01', 'annee' => 2026, 'type' => 'fixe'],
            ['nom' => 'Fête du Trône', 'date' => '2026-07-30', 'annee' => 2026, 'type' => 'fixe'],
            ['nom' => 'Journée de Oued Ed-Dahab', 'date' => '2026-08-14', 'annee' => 2026, 'type' => 'fixe'],
            ['nom' => 'Révolution du Roi et du Peuple', 'date' => '2026-08-20', 'annee' => 2026, 'type' => 'fixe'],
            ['nom' => 'Anniversaire de la Marche Verte', 'date' => '2026-11-06', 'annee' => 2026, 'type' => 'fixe'],
            ['nom' => 'Fête de l\'Indépendance', 'date' => '2026-11-18', 'annee' => 2026, 'type' => 'fixe'],
            
            // 2026 - Jours variables (islamiques) - Dates approximatives
            ['nom' => 'Aid Al-Fitr', 'date' => '2026-03-21', 'annee' => 2026, 'type' => 'variable'],
            ['nom' => 'Aid Al-Fitr (jour 2)', 'date' => '2026-03-22', 'annee' => 2026, 'type' => 'variable'],
            ['nom' => 'Aid Al-Adha', 'date' => '2026-05-28', 'annee' => 2026, 'type' => 'variable'],
            ['nom' => 'Aid Al-Adha (jour 2)', 'date' => '2026-05-29', 'annee' => 2026, 'type' => 'variable'],
            ['nom' => 'Nouvel An Hégirien 1448', 'date' => '2026-06-17', 'annee' => 2026, 'type' => 'variable'],
            ['nom' => 'Aid Al-Mawlid Annabaoui', 'date' => '2026-08-26', 'annee' => 2026, 'type' => 'variable'],
            ['nom' => 'Aid Al-Mawlid Annabaoui (jour 2)', 'date' => '2026-08-27', 'annee' => 2026, 'type' => 'variable'],
            
            // 2027 - Jours fixes
            ['nom' => 'Nouvel An', 'date' => '2027-01-01', 'annee' => 2027, 'type' => 'fixe'],
            ['nom' => 'Manifeste de l\'Indépendance', 'date' => '2027-01-11', 'annee' => 2027, 'type' => 'fixe'],
            ['nom' => 'Fête du Travail', 'date' => '2027-05-01', 'annee' => 2027, 'type' => 'fixe'],
            ['nom' => 'Fête du Trône', 'date' => '2027-07-30', 'annee' => 2027, 'type' => 'fixe'],
            ['nom' => 'Journée de Oued Ed-Dahab', 'date' => '2027-08-14', 'annee' => 2027, 'type' => 'fixe'],
            ['nom' => 'Révolution du Roi et du Peuple', 'date' => '2027-08-20', 'annee' => 2027, 'type' => 'fixe'],
            ['nom' => 'Anniversaire de la Marche Verte', 'date' => '2027-11-06', 'annee' => 2027, 'type' => 'fixe'],
            ['nom' => 'Fête de l\'Indépendance', 'date' => '2027-11-18', 'annee' => 2027, 'type' => 'fixe'],
            
            // 2027 - Jours variables (islamiques) - Dates approximatives
            ['nom' => 'Aid Al-Fitr', 'date' => '2027-03-11', 'annee' => 2027, 'type' => 'variable'],
            ['nom' => 'Aid Al-Fitr (jour 2)', 'date' => '2027-03-12', 'annee' => 2027, 'type' => 'variable'],
            ['nom' => 'Aid Al-Adha', 'date' => '2027-05-18', 'annee' => 2027, 'type' => 'variable'],
            ['nom' => 'Aid Al-Adha (jour 2)', 'date' => '2027-05-19', 'annee' => 2027, 'type' => 'variable'],
            ['nom' => 'Nouvel An Hégirien 1449', 'date' => '2027-06-07', 'annee' => 2027, 'type' => 'variable'],
            ['nom' => 'Aid Al-Mawlid Annabaoui', 'date' => '2027-08-16', 'annee' => 2027, 'type' => 'variable'],
            ['nom' => 'Aid Al-Mawlid Annabaoui (jour 2)', 'date' => '2027-08-17', 'annee' => 2027, 'type' => 'variable'],
        ];
        
        foreach ($joursFeries as $jourFerie) {
            JourFerie::create($jourFerie);
        }
    }
}