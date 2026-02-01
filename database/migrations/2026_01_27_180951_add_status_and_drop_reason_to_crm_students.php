<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('crm_students', function (Blueprint $table) {
            $table->enum('status', ['Enrolled', 'Dropped', 'Refund'])
                  ->default('Enrolled')
                  ->after('id');

            $table->text('drop_reason')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('crm_students', function (Blueprint $table) {
            $table->dropColumn(['status', 'drop_reason']);
        });
    }
};
