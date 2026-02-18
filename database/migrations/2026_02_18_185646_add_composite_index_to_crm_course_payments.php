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
                ['enrolled_course_id', 'is_deleted', 'payment_date'],
                'idx_cp_course_deleted_date'
            );
        });
    }

    public function down(): void
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {
            $table->dropIndex('idx_cp_course_deleted_date');
        });
    }
};
