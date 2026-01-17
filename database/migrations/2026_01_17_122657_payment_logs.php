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
        Schema::create('crm_enrolled_course_payment_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('enrolled_course_payment_id');

    $table->string('action'); // created | updated | deleted

    $table->json('old_data')->nullable();
    $table->json('new_data')->nullable();

    // WHO DID THIS
    $table->unsignedBigInteger('performed_by')->nullable();
    $table->string('performed_by_name')->nullable();
    $table->string('performed_by_email')->nullable();
    $table->string('performed_by_role')->nullable();

    // REQUEST INFO
    $table->string('ip_address')->nullable();
    $table->text('user_agent')->nullable();

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
        //
    }
};
