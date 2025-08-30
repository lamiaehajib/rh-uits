<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projets', function (Blueprint $table) {
            $table->id(); // id du projet
            $table->string('titre'); // Titre du projet
            $table->text('description')->nullable(); // Description du projet, peut être nulle
            
            // Clé étrangère pour lier le projet à l'utilisateur/client
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->date('date_debut'); // Date de début du projet
            $table->date('date_fin')->nullable(); // Date de fin du projet, peut être nulle
            $table->string('fichier')->nullable(); // Nom du fichier associé au projet
            $table->string('statut_projet')->default('en cours'); // Statut par défaut du projet
            
            $table->timestamps(); // Ajoute les colonnes created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projets');
    }
};
