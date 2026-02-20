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
        Schema::create('group_instructors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('group_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('instructor_id') // instructor is a user
                    ->nullable()
                    ->comment("References users.id where role = 'instructor'");
;

            $table->timestamps();

            $table->unique(['group_id', 'instructor_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gr', function (Blueprint $table) {
            //
        });
    }
};
