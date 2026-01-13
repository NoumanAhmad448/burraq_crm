<?php
// database/migrations/xxxx_add_admission_date_due_date_to_crm_enrolled_courses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdmissionDateDueDateToCrmEnrolledCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            $table->date('admission_date')->nullable()->after('total_fee');
            $table->date('due_date')->nullable()->after('admission_date');
        });
    }

    public function down()
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            $table->dropColumn(['admission_date', 'due_date']);
        });
    }
}