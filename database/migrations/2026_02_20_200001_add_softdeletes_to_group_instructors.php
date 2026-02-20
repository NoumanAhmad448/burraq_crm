<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeletesToGroupInstructors extends Migration
{
    public function up()
    {
        Schema::table('group_instructors', function (Blueprint $table) {
            $table->softDeletes()->after('instructor_id');
        });
    }

    public function down()
    {
        Schema::table('group_instructors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}