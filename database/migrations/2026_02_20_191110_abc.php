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
        Schema::table('group_course_progress', function (Blueprint $table) {
            Schema::table('group_course_progress', function (Blueprint $table) {
                // Drop foreign key first if it exists
                if (Schema::hasColumn('group_course_progress', 'course_id')) {
                    $table->dropForeign(['course_id']); // uses column name
                    $table->dropColumn('course_id');
                }

                if (Schema::hasColumn('group_course_progress', 'instructor_id')) {
                    $table->dropForeign(['instructor_id']); // if there was a foreign key
                    $table->dropColumn('instructor_id');
                }
            });
            // $table->dropIndex('group_course_progress_course_id_index');
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
