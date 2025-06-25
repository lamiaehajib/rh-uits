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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->date('date');
            $table->text('description');
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
            // --- Columns to add based on the controller ---
            $table->enum('priority', ['low', 'medium', 'high'])->default('low'); // Used in store, update, index
            $table->string('category', 100); // Used in store, update
            $table->enum('status', ['pending', 'in_progress', 'resolved', 'closed'])->default('pending'); // Used in store, update, updateStatus, index
            $table->string('reference')->unique(); // Used in store, generateReference, export
            $table->json('attachments')->nullable(); // Used in store, destroy, downloadAttachment
            $table->text('admin_notes')->nullable(); // Used in update, updateStatus
            $table->timestamp('resolved_at')->nullable(); // Used in updateStatus, export
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
        Schema::dropIfExists('reclamations');
    }
};
