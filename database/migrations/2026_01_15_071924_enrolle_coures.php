<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            // Soft-delete flag
            $table->tinyInteger('is_deleted')->default(0)->after('id');

            // Who deleted this course
            $table->unsignedBigInteger('deleted_by')->nullable()->after('is_deleted');

            // Optional timestamp when deleted
            $table->timestamp('deleted_at')->nullable()->after('deleted_by');

        });
    }

    public function down(): void
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['is_deleted', 'deleted_by', 'deleted_at']);
        });
    }
};
