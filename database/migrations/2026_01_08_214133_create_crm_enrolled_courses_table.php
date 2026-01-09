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
        Schema::create('crm_enrolled_courses', function (Blueprint $table) {
            $table->id(); // BD-00 OK
            $table->foreignId('student_id')->constrained('crm_students');
            $table->foreignId('course_id')->constrained('crm_courses');
            $table->bigInteger('total_fee');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_enrolled_courses');
    }
};
