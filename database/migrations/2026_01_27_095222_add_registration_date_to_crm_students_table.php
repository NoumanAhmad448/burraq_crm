<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('crm_students', function (Blueprint $table) {
            $table->date('registration_date')->nullable();
            // replace 'some_existing_column' with the column after which you want this field
        });
    }

    public function down()
    {
        Schema::table('crm_students', function (Blueprint $table) {
            $table->dropColumn('registration_date');
        });
    }
};
