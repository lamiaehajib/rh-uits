<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIduserFromProjects extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['iduser']);
            $table->dropColumn('iduser');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
        });
    }
}
