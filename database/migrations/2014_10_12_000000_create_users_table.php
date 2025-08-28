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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('tele')->nullable();
            $table->integer('code')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('poste')->nullable();
            $table->string('adresse')->nullable();
            $table->string('repos')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
             $table->boolean('is_active')->default(true);
             
            $table->rememberToken();
             $table->timestamp('last_login_at')->nullable();
            $table->integer('login_count')->default(0);
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
        Schema::dropIfExists('users');
    }
};
