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
        $table->foreignId('annule_par_user_id')
              ->nullable()
              ->constrained('users')
              ->onDelete('set null')
              ->after('statut'); // ou une autre colonne de votre choix
    });
}

public function down()
{
    Schema::table('rendez_vous', function (Blueprint $table) {
        $table->dropForeign(['annule_par_user_id']);
        $table->dropColumn('annule_par_user_id');
    });
}
};
