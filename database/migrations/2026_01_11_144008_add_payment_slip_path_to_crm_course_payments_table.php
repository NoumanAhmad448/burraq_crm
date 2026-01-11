<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentSlipPathToCrmCoursePaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {
            $table->string('payment_slip_path')->nullable()->after('paid_amount');
        });
    }

    public function down()
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {
            $table->dropColumn('payment_slip_path');
        });
    }
}
