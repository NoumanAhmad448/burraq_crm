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
        // database/migrations/xxxx_create_crm_payments_table.php
Schema::create('crm_payments', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('enrolled_course_id');
    $table->decimal('amount', 10, 2);
    $table->date('payment_date');
    $table->string('method')->nullable(); // cash, bank, etc
    $table->text('note')->nullable();
    $table->boolean('is_deleted')->default(0);
    $table->timestamps();

    $table->foreign('enrolled_course_id')
          ->references('id')
          ->on('crm_enrolled_courses');
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_payments');
    }
};
