<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmCertificatesTable extends Migration
{
    public function up()
    {
        Schema::create('crm_certificates', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('enrolled_course_id');
            $table->unsignedBigInteger('generated_by');

            $table->unsignedInteger('generated_count')->default(1);
            $table->dateTime('last_generated_at')->nullable();

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('crm_certificates');
    }
}
