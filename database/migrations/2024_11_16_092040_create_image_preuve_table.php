<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagePreuveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_preuve', function (Blueprint $table) {
            $table->id(); 
            $table->string('titre'); 
            $table->text('description')->nullable(); 
            $table->string('media'); 
            $table->date('date'); 
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_preuve');
    }
}
