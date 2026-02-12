<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('taches', function (Blueprint $table) {
            // تغيير نوع العمود من date إلى datetime
            $table->dateTime('date_fin_prevue')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('taches', function (Blueprint $table) {
            $table->date('date_fin_prevue')->nullable()->change();
        });
    }
};