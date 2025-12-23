<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('depenses_variables', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // PRIME, ACHAT_PCS, PRODUITS_MENAGES, FRAIS_BANQUE, JAWAZ, etc.
            $table->string('description')->nullable();
            $table->decimal('montant', 10, 2);
            $table->date('date_depense');
            $table->string('mois'); // 2024-01, 2024-02, etc.
            $table->enum('categorie', [
                'primes_repos',
                'achats_equipements', 
                'produits_menages',
                'frais_bancaires',
                'publications',
                'autres'
            ])->default('autres');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Li dar depense
            $table->foreignId('beneficiaire_id')->nullable()->constrained('users')->onDelete('set null'); // Li 3tih prime
            $table->string('justificatif')->nullable(); // Facture/reçu file path
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('depenses_variables');
    }
};