<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Client
            $table->string('titre');
            $table->text('description')->nullable();
            $table->dateTime('date_heure'); // Date et heure du rendez-vous
            $table->string('lieu')->nullable(); // Lieu du rendez-vous
            $table->enum('statut', ['programmé', 'confirmé', 'terminé', 'annulé'])->default('programmé');
            $table->text('notes')->nullable(); // Notes après le rendez-vous
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rendez_vous');
    }
};