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
        Schema::create('crm_students', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('father_name');
    $table->string('cnic')->unique();
    $table->string('mobile');
    $table->string('email')->nullable();
    $table->string('photo')->nullable();
    $table->date('admission_date')->nullable();
    $table->date('due_date')->nullable();
    $table->decimal('total_fee', 10, 2)->nullable();
    $table->decimal('paid_fee', 10, 2)->default(0)->nullable();
    $table->decimal('remaining_fee', 10, 2)->default(0)->nullable();
    $table->boolean('is_deleted')->default(false);
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
        Schema::dropIfExists('crm_students');
    }
};
