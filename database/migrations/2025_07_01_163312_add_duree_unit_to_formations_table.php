<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            // Add the new 'duree_unit' column
            // Place it after the 'duree' column for logical grouping
            $table->string('duree_unit')->nullable()->after('duree'); // 'jours', 'semaines', 'mois'

            // If you have existing 'duree' values and they represent 'days',
            // you might want to run an update query here to set default unit for old records:
            // \DB::statement("UPDATE formations SET duree_unit = 'jours' WHERE duree IS NOT NULL AND duree_unit IS NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            // Drop the 'duree_unit' column if rolling back
            $table->dropColumn('duree_unit');
        });
    }
};