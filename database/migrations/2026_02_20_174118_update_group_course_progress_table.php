<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         DB::statement("
            ALTER TABLE group_course_progress
            MODIFY progress_pct BOOLEAN NOT NULL DEFAULT 0
        ");
        Schema::table('group_course_progress', function (Blueprint $table) {
            // Change progress_pct to boolean
            // $table->boolean('progress_pct')->change();

            // Add new nullable module field
            $table->text('module')->nullable()->after('progress_pct');
        });
    }

    public function down(): void
    {
        Schema::table('group_course_progress', function (Blueprint $table) {
            // Revert progress_pct back (adjust type if needed)
            $table->integer('progress_pct')->change();

            // Drop module column
            $table->dropColumn('module');
        });
    }
};