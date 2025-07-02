<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tache_users', function (Blueprint $table) {
            // Clé étrangère vers la table 'taches'
            $table->foreignId('tache_id')->constrained('taches')->onDelete('cascade');
            // Clé étrangère vers la table 'users'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Définir une clé primaire composite pour éviter les doublons
            $table->primary(['tache_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tache_users');
    }
};