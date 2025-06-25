`<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            $table->string('titre');
            $table->string('nomclient');
            $table->string('ville');
            $table->text('bessoins');
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
            $projectData['date_project'] = now()->format('Y-m-d');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
