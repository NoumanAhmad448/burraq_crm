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
        Schema::create('crm_course_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrolled_course_id')->constrained('crm_enrolled_courses');
            $table->decimal('paid_amount', 10, 2);
            $table->foreignId('payment_by')->constrained('users', "id");
            $table->date('paid_at');
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
        Schema::dropIfExists('crm_enrolled_course_payments');
    }
};
