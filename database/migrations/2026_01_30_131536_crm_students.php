<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('crm_students', function (Blueprint $table) {
            $table->index(
                ['is_deleted', 'registration_date'],
                'idx_students_deleted_regdate'
            );
        });
    }

    public function down(): void
    {
        Schema::table('crm_students', function (Blueprint $table) {
            $table->dropIndex('idx_students_deleted_regdate');
        });
    }
};
