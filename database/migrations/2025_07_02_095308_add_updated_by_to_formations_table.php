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
            // Ajoute la colonne updated_by. Elle est nullable par défaut.
            // unsignedBigInteger pour qu'elle puisse être une clé étrangère vers les IDs des utilisateurs.
            // after('created_by') pour la positionner logiquement après created_by.
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');

            // Optionnel: Ajouter une contrainte de clé étrangère
            // Assurez-vous que votre table 'users' existe et que la colonne 'id' est bien unsignedBigInteger.
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            // Supprime la contrainte de clé étrangère avant de supprimer la colonne
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
    }
};
