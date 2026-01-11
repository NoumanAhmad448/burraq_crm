<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCrmCoursePaymentsAddSlipAndIsDeleted extends Migration
{
    public function up()
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {

            // if (!Schema::hasColumn('crm_course_payments', 'payment_slip_path')) {
            //     $table->string('payment_slip_path')->nullable()->after('paid_amount');
            // }

            if (!Schema::hasColumn('crm_course_payments', 'is_deleted')) {
                $table->boolean('is_deleted')->default(false)->after('payment_slip_path');
            }

        });
    }

    public function down()
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {

            if (Schema::hasColumn('crm_course_payments', 'payment_slip_path')) {
                $table->dropColumn('payment_slip_path');
            }

            if (Schema::hasColumn('crm_course_payments', 'is_deleted')) {
                $table->dropColumn('is_deleted');
            }

        });
    }
}
