<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeletesToGroupEnrollments extends Migration
{
    public function up()
    {
        Schema::table('group_enrollments', function (Blueprint $table) {
            $table->softDeletes()->after('crm_enrolled_course_id');
        });
    }

    public function down()
    {
        Schema::table('group_enrollments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}