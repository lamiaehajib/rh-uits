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
        Schema::table('taches', function (Blueprint $table) {
            // Add 'titre' column as a string, after 'description' for logical grouping
            $table->string('titre')->after('description')->nullable();

            // Add 'priorite' column as an enum with specific values, after 'titre'
            // 'faible' (low), 'moyen' (medium), 'élevé' (high)
            $table->enum('priorite', ['faible', 'moyen', 'élevé'])->default('moyen')->after('titre');

            // Add 'retour' column as text for feedback or return notes, after 'status'
            $table->text('retour')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('taches', function (Blueprint $table) {
            // Drop the columns if the migration is rolled back
            $table->dropColumn(['titre', 'priorite', 'retour']);
        });
    }
};


  
