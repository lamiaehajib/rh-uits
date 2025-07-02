<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1: Migrer les données de 'iduser' vers la nouvelle table pivot
        if (Schema::hasColumn('objectifs', 'iduser')) {
             DB::table('objectifs')->get()->each(function ($objectif) {
                if (isset($objectif->iduser) && $objectif->iduser !== null) {
                    DB::table('objectif_user')->insert([
                        'objectif_id' => $objectif->id,
                        'user_id' => $objectif->iduser,
                        // 'created_at' => now(), // Décommente si ta table pivot a des timestamps
                        // 'updated_at' => now(), // Décommente si ta table pivot a des timestamps
                    ]);
                }
            });
        }

        // Étape 2: Supprimer la colonne 'iduser' de la table 'objectifs'
        Schema::table('objectifs', function (Blueprint $table) {
            // Vérifier si la clé étrangère existe avant de tenter de la supprimer
            $foreignKeyExists = DB::select(DB::raw("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND TABLE_SCHEMA = '".DB::connection()->getDatabaseName()."'
                AND TABLE_NAME = 'objectifs'
                AND CONSTRAINT_NAME = 'objectifs_iduser_foreign'
            "));
            
            if (!empty($foreignKeyExists)) {
                $table->dropForeign(['iduser']);
            }

            // Vérifier si la colonne 'iduser' existe avant de tenter de la supprimer
            if (Schema::hasColumn('objectifs', 'iduser')) {
                $table->dropColumn('iduser');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objectifs', function (Blueprint $table) {
            if (!Schema::hasColumn('objectifs', 'iduser')) {
                 $table->foreignId('iduser')->nullable()->constrained('users')->onDelete('cascade');
            }
        });
        // Ici, la logique de rollback de la table pivot n'est pas ajoutée automatiquement
        // pour éviter une perte de données complexe si des objectifs ont été assignés à plusieurs utilisateurs.
    }
};