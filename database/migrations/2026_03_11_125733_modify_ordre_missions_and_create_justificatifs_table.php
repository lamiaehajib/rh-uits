<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Modifier ordre_missions : date → datetime ──────────────
        Schema::table('ordre_missions', function (Blueprint $table) {
            // Remplacer date par datetime
            $table->dateTime('date_depart')->change();
            $table->dateTime('date_retour')->change();
        });

        // ── 2. Table justificatifs ────────────────────────────────────
        Schema::create('ordre_mission_justificatifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordre_mission_id')->constrained('ordre_missions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nom_fichier');          // nom original
            $table->string('chemin');               // path stocké
            $table->string('type_mime');            // image/jpeg, application/pdf, etc.
            $table->unsignedBigInteger('taille');   // en octets
            $table->enum('type_doc', [
                'bon_transport',
                'facture_hotel',
                'facture_repas',
                'ticket',
                'autre'
            ])->default('autre');
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordre_mission_justificatifs');

        Schema::table('ordre_missions', function (Blueprint $table) {
            $table->date('date_depart')->change();
            $table->date('date_retour')->change();
        });
    }
};