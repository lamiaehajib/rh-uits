<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Objectif;
use Carbon\Carbon; // Make sure to import Carbon

class UpdateObjectifProgress extends Command
{
    protected $signature = 'objectifs:update-progress';
    protected $description = 'Updates the progress of objectives based on their dates.';

    public function handle()
    {
        $objectifs = Objectif::all(); // Or a more specific query for pending/active objectives

        foreach ($objectifs as $objectif) {
            $calculatedProgress = $this->calculateObjectifProgressInternal($objectif);

            // Only update if the calculated progress is different from the stored one
            // and not manually set to 100 (which implies completion by user, not automatic)
            if ($objectif->progress !== $calculatedProgress && $objectif->progress !== 100) {
                $objectif->progress = $calculatedProgress;
                $objectif->save();
                $this->info("Updated Objectif #{$objectif->id} progress to {$calculatedProgress}%");
            }

            // Optionally, trigger a notification if needs explanation and wasn't already triggered
            // This would require an additional field like `explanation_requested_at` or `is_overdue`
            // and a notification specific for this.
        }

        $this->info('Objective progress update complete.');
        return Command::SUCCESS;
    }

    /**
     * Duplicates the progress calculation logic from the controller for the command.
     * This avoids making the controller method public if it's only meant for internal use.
     * In a larger application, this logic might reside in a dedicated service class.
     */
    private function calculateObjectifProgressInternal(Objectif $objectif): int
    {
        $startDate = Carbon::parse($objectif->date);
        $currentDate = Carbon::now();

        if ($currentDate->lessThan($startDate)) {
            return 0;
        }

        if ($objectif->status === 'mois') {
            $endDate = $startDate->copy()->endOfMonth();
            $totalDays = $startDate->daysInMonth;
            $daysPassed = $currentDate->diffInDays($startDate);
            $daysPassed = min($daysPassed, $totalDays); // Cap days passed to total days

            if ($currentDate->greaterThanOrEqualTo($endDate)) {
                return 100;
            } elseif ($daysPassed >= ($totalDays / 2)) {
                return 50;
            } else {
                return 0;
            }
        } elseif ($objectif->status === 'annee') {
            $endDate = $startDate->copy()->addYear()->subDay();
            $totalMonths = 12;
            $monthsPassed = $currentDate->diffInMonths($startDate);
            $monthsPassed = min($monthsPassed, $totalMonths); // Cap months passed to total months

            if ($currentDate->greaterThanOrEqualTo($endDate)) {
                return 100;
            } elseif ($monthsPassed >= ($totalMonths / 2)) {
                return 50;
            } else {
                return 0;
            }
        }

        return $objectif->progress;
    }
}