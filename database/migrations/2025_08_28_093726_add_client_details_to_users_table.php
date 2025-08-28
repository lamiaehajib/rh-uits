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
        Schema::table('users', function (Blueprint $table) {
            // Champos jdads bach tmiyiz bin les clients
            // Yakhtar wach particulier wla entreprise, w ghanzidouh mora 'password'
            $table->string('type_client')->default('particulier')->after('password');
            // Simiyat la societe, w ghanzidouha mora 'type_client'
            $table->string('societe_name')->nullable()->after('type_client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['type_client', 'societe_name']);
        });
    }
};

