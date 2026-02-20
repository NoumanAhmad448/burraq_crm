<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            if (Schema::hasColumn('crm_enrolled_courses', 'group_id')) {
                $table->dropForeign(['group_id']); // uses column name
                $table->dropColumn('group_id');
            }

            if (Schema::hasColumn('crm_enrolled_courses', 'course_id')) {
                $table->dropForeign(['course_id']); // if there was a foreign key
                $table->dropColumn('course_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
