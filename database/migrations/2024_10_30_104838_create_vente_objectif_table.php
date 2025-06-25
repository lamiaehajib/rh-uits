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
        Schema::create('vente_objectif', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('description');
            $table->integer('Quantitépc');
            $table->decimal('prixachat', 8, 2);
            $table->decimal('totalachat', 8, 2);
            $table->decimal('prixvendu', 8, 2);
            $table->integer('Quantitévendu');
            $table->decimal('totalvendu', 8, 2);
            $table->decimal('marge', 8, 2);
            $table->integer('enstock');
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
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
        Schema::dropIfExists('vente_objectif');
    }
};
