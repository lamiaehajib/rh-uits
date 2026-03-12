<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordre_missions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('traite_par')->nullable()->constrained('users')->onDelete('set null');

            // Destination & dates
            $table->string('destination');
            $table->string('objet'); // motif / but de la mission
            $table->date('date_depart');
            $table->date('date_retour');
            $table->integer('duree_jours')->virtualAs('DATEDIFF(date_retour, date_depart) + 1');

            // Transport
            $table->enum('moyen_transport', ['voiture_personnelle', 'train', 'avion', 'bus', 'autre'])->default('train');
            $table->string('moyen_transport_autre')->nullable(); // si "autre"

            // Budget prévisionnel
            $table->decimal('frais_transport', 10, 2)->default(0);
            $table->decimal('frais_hebergement', 10, 2)->default(0);
            $table->decimal('frais_repas', 10, 2)->default(0);
            $table->decimal('frais_divers', 10, 2)->default(0);
            $table->decimal('avance_demandee', 10, 2)->default(0); // montant avance souhaitée

            // Statut workflow
            $table->enum('statut', [
                'en_attente',
                'approuve',
                'refuse',
                'annule',
                'cloture'   // après retour et remboursement
            ])->default('en_attente');

            $table->text('motif_refus')->nullable();
            $table->text('commentaire_admin')->nullable();
            $table->text('notes_employe')->nullable();

            // Après mission
            $table->decimal('frais_reels', 10, 2)->nullable();
            $table->decimal('avance_versee', 10, 2)->nullable();
            $table->decimal('solde_rembourse', 10, 2)->nullable(); // positif = remboursé à l'employé
            $table->date('date_traitement')->nullable();
            $table->date('date_cloture')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordre_missions');
    }
};