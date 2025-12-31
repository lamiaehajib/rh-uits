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
        Schema::table('suivre_pointage', function (Blueprint $table) {
            $table->string('type')->default('presence')->after('date_pointage'); // presence, absence
            $table->text('justificatif')->nullable()->after('description'); // Texte justification
            $table->string('justificatif_file')->nullable()->after('justificatif'); // Fichier (image/PDF)
            $table->boolean('justificatif_valide')->default(false)->after('justificatif_file'); // Validé par admin
            $table->timestamp('justificatif_soumis_at')->nullable()->after('justificatif_valide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suivre_pointage', function (Blueprint $table) {
            $table->dropColumn(['type', 'justificatif', 'justificatif_file', 'justificatif_valide', 'justificatif_soumis_at']);
        });
    }
};