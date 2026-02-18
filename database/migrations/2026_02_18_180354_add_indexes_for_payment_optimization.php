<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {
            $table->index(
                ['is_deleted', 'payment_date', 'enrolled_course_id'],
                'idx_payments_deleted_date_course1'
            );

            $table->index('type', 'idx_payments_type1');
        });

        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            $table->index(
                ['is_deleted', 'student_id'],
                'idx_courses_deleted_student1'
            );
        });

        Schema::table('crm_students', function (Blueprint $table) {
            $table->index('is_deleted', 'idx_students_deleted');
        });
    }

    public function down(): void
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {
            $table->dropIndex('idx_payments_deleted_date_course1');
            $table->dropIndex('idx_payments_type');
        });

        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            $table->dropIndex('idx_courses_deleted_student1');
        });

        Schema::table('crm_students', function (Blueprint $table) {
            $table->dropIndex('idx_students_deleted1');
        });
    }
};
