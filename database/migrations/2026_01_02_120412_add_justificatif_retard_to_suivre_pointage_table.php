<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('suivre_pointage', function (Blueprint $table) {
            $table->text('justificatif_retard')->nullable()->after('justificatif_file');
            $table->string('justificatif_retard_file')->nullable()->after('justificatif_retard');
            $table->boolean('retard_justifie')->default(false)->after('justificatif_retard_file');
            $table->timestamp('justificatif_retard_soumis_at')->nullable()->after('retard_justifie');
        });
    }

    public function down()
    {
        Schema::table('suivre_pointage', function (Blueprint $table) {
            $table->dropColumn([
                'justificatif_retard',
                'justificatif_retard_file',
                'retard_justifie',
                'justificatif_retard_soumis_at'
            ]);
        });
    }
};