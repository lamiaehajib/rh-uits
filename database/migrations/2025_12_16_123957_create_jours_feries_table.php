<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jours_feries', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->date('date');
            $table->integer('annee');
            $table->enum('type', ['fixe', 'variable']); // fixe pour dates fixes, variable pour dates islamiques
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jours_feries');
    }
};