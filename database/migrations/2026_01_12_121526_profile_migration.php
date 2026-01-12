<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Add missing HR-related fields if they don't exist
            if (!Schema::hasColumn('profiles', 'mobile_no')) {
                $table->string('mobile_no', 20)->after('user_id');
            }
            if (!Schema::hasColumn('profiles', 'father_name')) {
                $table->string('father_name', 255)->after('mobile_no');
            }
            if (!Schema::hasColumn('profiles', 'cnic')) {
                $table->string('cnic', 25)->after('father_name');
            }
            if (!Schema::hasColumn('profiles', 'guardian_name')) {
                $table->string('guardian_name', 255)->nullable()->after('cnic');
            }
            if (!Schema::hasColumn('profiles', 'home_address')) {
                $table->text('home_address')->nullable()->after('guardian_name');
            }
            if (!Schema::hasColumn('profiles', 'cnic_photo_path')) {
                $table->string('cnic_photo_path')->nullable()->after('home_address');
            }
            if (!Schema::hasColumn('profiles', 'resume_path')) {
                $table->string('resume_path')->nullable()->after('cnic_photo_path');
            }
            if (!Schema::hasColumn('profiles', 'other_document_path')) {
                $table->string('other_document_path')->nullable()->after('resume_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $columns = [
                'mobile_no',
                'father_name',
                'cnic',
                'guardian_name',
                'home_address',
                'cnic_photo_path',
                'resume_path',
                'other_document_path',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('profiles', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
