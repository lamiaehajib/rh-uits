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
        $table->foreignId('confirme_par_user_id')->nullable()->after('statut')->constrained('users')->onDelete('set null');
    });
}

public function down(): void
{
    Schema::table('rendez_vous', function (Blueprint $table) {
        $table->dropForeign(['confirme_par_user_id']);
        $table->dropColumn('confirme_par_user_id');
    });
}
};
