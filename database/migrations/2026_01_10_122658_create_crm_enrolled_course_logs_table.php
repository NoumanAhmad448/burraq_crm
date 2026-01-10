<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crm_enrolled_course_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('enrolled_course_id');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('action', ['created', 'updated']);

            $table->decimal('total_fee', 10, 2);

            $table->json('snapshot')->nullable();
            $table->timestamp('logged_at')->useCurrent();

            $table->index(['student_id', 'course_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_enrolled_course_logs');
    }
};
