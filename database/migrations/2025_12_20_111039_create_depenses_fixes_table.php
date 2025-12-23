<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('depenses_fixes', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // SALAIRE, LOYER, CONNEXION, LYDEC, ABONNEMENT, etc.
            $table->string('description')->nullable();
            $table->decimal('montant', 10, 2);
            $table->date('date_depense');
            $table->string('mois'); // 2024-01, 2024-02, etc.
            $table->enum('statut', ['payé', 'en_attente', 'annulé'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Li dar depense
            $table->foreignId('salarie_id')->nullable()->constrained('users')->onDelete('cascade'); // Ila kan salaire
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('depenses_fixes');
    }
};