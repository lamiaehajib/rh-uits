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
        Schema::create('projet_user', function (Blueprint $table) {
            $table->id();

            // Clé étrangère pour le projet
            $table->foreignId('projet_id')->constrained('projets')->onDelete('cascade');

            // Clé étrangère pour l'utilisateur (client)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Pour éviter les doublons, on s'assure que la combinaison des deux est unique
            $table->unique(['projet_id', 'user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projet_user');
    }
};
