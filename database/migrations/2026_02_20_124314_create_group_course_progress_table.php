<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_course_progress', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->constrained('groups')
                ->cascadeOnDelete();

            $table->decimal('progress_pct', 5, 2)->default(0);

            $table->timestamps();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_course_progress');
    }
};