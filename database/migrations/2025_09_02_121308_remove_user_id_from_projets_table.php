<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Supprime la clé étrangère d'abord
            $table->dropColumn('user_id');   // Supprime le champ
        });
    }
    public function down()
    {
        Schema::table('projets', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        });
    }
};
