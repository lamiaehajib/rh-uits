<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->time('heuredebut')->nullable()->after('datedebut');
        });
    }

    public function down()
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->dropColumn('heuredebut');
        });
    }
};