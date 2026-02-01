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
        // database/migrations/xxxx_add_due_date_to_crm_inquiries_table.php
        Schema::table('crm_inquiries', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_inquiries', function (Blueprint $table) {
            //
        });
    }
};
