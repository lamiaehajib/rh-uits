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
   public function up()
{
    Schema::table('suivre_pointage', function (Blueprint $table) {
        // Add user_latitude and user_longitude if they don't exist
        if (!Schema::hasColumn('suivre_pointage', 'user_latitude')) {
            $table->double('user_latitude', 10, 8)->nullable()->after('localisation');
        }
        if (!Schema::hasColumn('suivre_pointage', 'user_longitude')) {
            $table->double('user_longitude', 11, 8)->nullable()->after('user_latitude');
        }
        // Add date_pointage if it doesn't exist
        if (!Schema::hasColumn('suivre_pointage', 'date_pointage')) {
             $table->date('date_pointage')->nullable()->after('heure_arrivee');
        }
    });
}

public function down()
{
    Schema::table('suivre_pointage', function (Blueprint $table) {
        if (Schema::hasColumn('suivre_pointage', 'user_latitude')) {
            $table->dropColumn('user_latitude');
        }
        if (Schema::hasColumn('suivre_pointage', 'user_longitude')) {
            $table->dropColumn('user_longitude');
        }
        if (Schema::hasColumn('suivre_pointage', 'date_pointage')) {
            $table->dropColumn('date_pointage');
        }
    });
}
};
