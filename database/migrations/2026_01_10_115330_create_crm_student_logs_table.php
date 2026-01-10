<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_student_logs', function (Blueprint $table) {
            $table->id();

            // Who & what
            $table->unsignedBigInteger('crm_student_id');
            $table->unsignedBigInteger('user_id')->nullable();

            // Action metadata
            $table->enum('action', ['created', 'updated']);

            // Snapshot of student data (JSON = future-proof)
            $table->json('student_snapshot');

            $table->timestamp('logged_at')->useCurrent();

            // Indexes
            $table->index('crm_student_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_student_logs');
    }
};

