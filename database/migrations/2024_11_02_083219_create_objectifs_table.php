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
        Schema::create('objectifs', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', ['formations', 'projets','vente']);
            $table->text('description');
            $table->string('ca');
            $table->enum('status', ['mois', 'annee']);
            $table->text('afaire');
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');

            // --- Add these lines ---
            $table->integer('progress')->default(0); // For the progress bar (0-100%)
             $table->text('explanation_for_incomplete')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // To track who created the objective
            // --- End of additions ---

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
        Schema::dropIfExists('objectifs');
    }
};