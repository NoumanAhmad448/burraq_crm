<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('crm_course_payments', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_date']);
        });
    }
};
