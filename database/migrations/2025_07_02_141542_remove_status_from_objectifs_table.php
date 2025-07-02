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
        Schema::table('objectifs', function (Blueprint $table) {
            // Drop the 'status' column from the objectifs table
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objectifs', function (Blueprint $table) {
            // Re-add the 'status' column if the migration is rolled back
            // You might need to adjust the enum values if they were different
            $table->enum('status', ['mois', 'annee'])->nullable();
        });
    }
};
