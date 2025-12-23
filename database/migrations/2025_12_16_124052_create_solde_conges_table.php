<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solde_conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('annee');
            $table->integer('total_jours')->default(18); // 18 jours par an
            $table->integer('jours_utilises')->default(0);
            $table->integer('jours_restants')->default(18);
            $table->timestamps();
            
            // Un seul solde par utilisateur par année
            $table->unique(['user_id', 'annee']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('solde_conges');
    }
};