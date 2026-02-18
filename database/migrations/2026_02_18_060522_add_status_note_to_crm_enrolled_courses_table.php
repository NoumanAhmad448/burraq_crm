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
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            $table->text('status_note')->nullable()->after('status');
            $table->timestamp('status_updated_at')->nullable()->after('status_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_enrolled_courses', function (Blueprint $table) {
            //
        });
    }
};
