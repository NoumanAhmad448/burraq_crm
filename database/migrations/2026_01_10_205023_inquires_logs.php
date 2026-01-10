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
        Schema::create('crm_inquiry_logs', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('inquiry_id')->nullable();
    $table->string('action'); // created, updated, deleted, restored

    $table->json('old_data')->nullable();
    $table->json('new_data')->nullable();

    $table->unsignedBigInteger('action_by')->nullable();
    $table->string('ip_address')->nullable();

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
