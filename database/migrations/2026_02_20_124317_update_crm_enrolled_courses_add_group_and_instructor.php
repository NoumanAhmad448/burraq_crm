<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {

            $table->foreignId('group_id')
                ->nullable()
                ->after('id')
                ->constrained('groups')
                ->nullOnDelete();

            $table->integer('instructor_id')
                ->nullable();

            $table->index('group_id');
            $table->index('instructor_id');
        });
    }

    public function down(): void
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {

            $table->dropForeign(['group_id']);
            $table->dropForeign(['instructor_id']);

            $table->dropIndex(['group_id']);
            $table->dropIndex(['instructor_id']);
            $table->dropIndex(['course_id', 'student_id']);

            $table->dropColumn(['group_id', 'instructor_id']);
        });
    }
};