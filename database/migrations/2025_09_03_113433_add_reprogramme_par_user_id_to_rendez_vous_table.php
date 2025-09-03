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
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->foreignId('reprogramme_par_user_id')->nullable()->after('annule_par_user_id')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropForeign(['reprogramme_par_user_id']);
            $table->dropColumn('reprogramme_par_user_id');
        });
    }
};
