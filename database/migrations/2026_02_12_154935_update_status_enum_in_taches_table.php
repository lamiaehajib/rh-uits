<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Ajouter le statut 'annulé' à la colonne status de la table taches.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE taches 
            MODIFY COLUMN status ENUM('nouveau', 'en cours', 'termine', 'annulé') 
            NOT NULL DEFAULT 'nouveau'
        ");
    }

    /**
     * Reverse the migrations.
     * Retirer le statut 'annulé' (remet l'ancien ENUM).
     * Attention : les tâches avec status='annulé' seront affectées.
     */
    public function down(): void
    {
        // Remettre les tâches annulées en 'nouveau' avant de supprimer la valeur ENUM
        DB::statement("
            UPDATE taches SET status = 'nouveau' WHERE status = 'annulé'
        ");

        DB::statement("
            ALTER TABLE taches 
            MODIFY COLUMN status ENUM('nouveau', 'en cours', 'termine') 
            NOT NULL DEFAULT 'nouveau'
        ");
    }
};