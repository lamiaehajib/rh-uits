<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // N'oubliez pas d'importer la façade DB

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1: Migrer les données de 'iduser' vers la nouvelle table pivot
        // Nous conservons ce bloc car il est possible que des données existent encore même si la colonne a été supprimée après une manipulation manuelle ou partielle
        // Laravel permet de récupérer les attributs même s'ils ne sont pas directement dans le schéma si la base de données les contient.
        // C'est une bonne pratique de garder cette partie pour s'assurer que si des données subsistent, elles soient transférées.

        // Avant de migrer les données, vérifions si la colonne 'iduser' existe encore dans la table 'taches'
        if (Schema::hasColumn('taches', 'iduser')) {
             DB::table('taches')->get()->each(function ($tache) {
                if (isset($tache->iduser) && $tache->iduser !== null) {
                    DB::table('tache_users')->insert([
                        'tache_id' => $tache->id,
                        'user_id' => $tache->iduser,
                        // 'created_at' => now(), // Décommente si ta table pivot a des timestamps
                        // 'updated_at' => now(), // Décommente si ta table pivot a des timestamps
                    ]);
                }
            });
        }


        // Étape 2: Supprimer la colonne 'iduser' de la table 'taches'
        Schema::table('taches', function (Blueprint $table) {
          
            $foreignKeyExists = DB::select(DB::raw("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE CONSTRAINT_TYPE = 'FOREIGN KEY'
                AND TABLE_SCHEMA = '".DB::connection()->getDatabaseName()."'
                AND TABLE_NAME = 'taches'
                AND CONSTRAINT_NAME = 'taches_iduser_foreign'
            "));
            
            if (!empty($foreignKeyExists)) {
                $table->dropForeign(['iduser']);
            }

            // Vérifier si la colonne 'iduser' existe avant de tenter de la supprimer
            if (Schema::hasColumn('taches', 'iduser')) {
                $table->dropColumn('iduser');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ce bloc peut rester tel quel, car il ajoute la colonne si elle manque.
        Schema::table('taches', function (Blueprint $table) {
            if (!Schema::hasColumn('taches', 'iduser')) {
                 $table->foreignId('iduser')->nullable()->constrained('users')->onDelete('cascade');
            }
        });
    }
};