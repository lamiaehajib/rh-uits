<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('nombre_jours_demandes'); // Jours demandés par l'utilisateur
            $table->integer('nombre_jours_ouvrables'); // Jours ouvrables réels (sans repos/fériés)
            $table->text('motif');
            $table->enum('statut', ['en_attente', 'approuve', 'refuse'])->default('en_attente');
            $table->text('commentaire_admin')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('traite_le')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conges');
    }
};