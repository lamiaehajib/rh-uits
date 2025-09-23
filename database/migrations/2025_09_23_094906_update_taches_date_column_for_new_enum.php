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
     public function up(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            // Kanbedlou l'enum dyal la colonne 'date' bach nzidou 'heure' w 'minute'
            $table->enum('date', ['jour', 'semaine', 'mois', 'heure', 'minute'])->change();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taches', function (Blueprint $table) {
            // Kanreja3ou l'enum kima kan ila bghina n'annuler la migration
            $table->enum('date', ['jour', 'semaine', 'mois'])->change();
        });
    }
};
