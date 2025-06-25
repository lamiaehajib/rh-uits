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
        Schema::create('_dashboard', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
            $table->foreignId('idtach')->constrained('taches')->onDelete('cascade');
            $table->foreignId('idproject')->constrained('projects')->onDelete('cascade');
            $table->foreignId('idformation')->constrained('formations')->onDelete('cascade');
            $table->foreignId('idvente_objectif')->constrained('vente_objectif')->onDelete('cascade');
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
        Schema::dropIfExists('_dashboard');
    }
};
