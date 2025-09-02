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
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Supprime la contrainte de clé étrangère
            $table->dropColumn('user_id'); // Supprime la colonne 'user_id'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            // Pour la méthode `down`, on recrée la colonne
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }
};
