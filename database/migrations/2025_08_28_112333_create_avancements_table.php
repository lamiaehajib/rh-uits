<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('avancements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->string('etape'); // Nom de l'étape
            $table->text('description');
            $table->integer('pourcentage')->default(0); // Pourcentage d'avancement (0-100)
            $table->enum('statut', ['en cours', 'terminé', 'bloqué'])->default('en cours');
            $table->date('date_prevue')->nullable(); // Date prévue pour cette étape
            $table->date('date_realisee')->nullable(); // Date réelle de réalisation
            $table->text('commentaires')->nullable();
            $table->string('fichiers')->nullable(); // Fichiers joints pour cette étape
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avancements');
    }
};