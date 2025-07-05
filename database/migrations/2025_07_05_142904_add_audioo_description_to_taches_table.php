<?php

use App\Models\Tache;


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAudiooDescriptionToTachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taches', function (Blueprint $table) {
            // Hna, ma ghadi ndirou walou l-'description'. Ghadi tbqa kif ma hiya.

            // Nzido la colonne 'audio_description_path'
            // Ghadi tkoun nullable hit yqadd tkoun description text
            // 'after('description')' bach tji mor colonne description
            $table->string('audio_description_path')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taches', function (Blueprint $table) {
            // Nhaydou la colonne 'audio_description_path'
            $table->dropColumn('audio_description_path');

            // Hna, ma ghadi ndirou walou l-'description' f-down() method, hit ma bedelnaha f-up().
        });
    }
}