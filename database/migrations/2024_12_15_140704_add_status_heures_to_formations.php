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
        Schema::table('formations', function (Blueprint $table) {
            $table->enum('statut', ['fini', 'encour', 'nouveu'])->default('nouveu');
            $table->integer('nombre_heures')->nullable();
            $table->integer('nombre_seances')->nullable();
            $table->decimal('prix', 8, 2)->nullable();
            $table->integer('duree')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn(['statut', 'nombre_heures', 'nombre_seances', 'prix', 'duree']);
        });
    }
};
