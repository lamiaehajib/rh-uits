<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        Schema::create('projectt_user', function (Blueprint $table) {
            // Clés étrangères qui lient les deux tables
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Définir la clé primaire composée pour garantir l'unicité des paires
            $table->primary(['project_id', 'user_id']);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('project_user');
    }
};
