<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');      // the HR/User affected
            $table->unsignedBigInteger('performed_by'); // who did it
            $table->string('module');                   // HR, USERS
            $table->string('action');                   // create, update, delete
            $table->string('model');                    // Profile or User
            $table->unsignedBigInteger('record_id');    // affected record id

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
