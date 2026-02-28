<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | test_students
        |--------------------------------------------------------------------------
        */
        if (!Schema::hasTable('test_students')) {
            Schema::create('test_students', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('mobile');
                $table->string('profile_photo')->nullable();
                $table->timestamps();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | test_courses
        |--------------------------------------------------------------------------
        */
        if (!Schema::hasTable('test_courses')) {
            Schema::create('test_courses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->decimal('price', 10, 2);
                $table->timestamps();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | test_student_courses
        |--------------------------------------------------------------------------
        */
        if (!Schema::hasTable('test_student_courses')) {
            Schema::create('test_student_courses', function (Blueprint $table) {
                $table->id();

                // Using unsignedBigInteger (NO constrained / NO cascade)
                $table->unsignedBigInteger('test_student_id');
                $table->unsignedBigInteger('test_course_id');

                // Price snapshot
                $table->decimal('original_price', 10, 2);
                $table->integer('coupon_percentage')->nullable();
                $table->decimal('final_price', 10, 2);

                // Moved here as requested
                $table->date('admission_date');
                $table->date('due_date')->nullable();

                $table->timestamps();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | test_payments
        |--------------------------------------------------------------------------
        */
        if (!Schema::hasTable('test_payments')) {
            Schema::create('test_payments', function (Blueprint $table) {
                $table->id();

                $table->unsignedBigInteger('test_student_course_id');

                $table->decimal('amount', 10, 2);
                $table->date('payment_date');
                $table->string('payment_slip')->nullable();

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('test_payments');
        Schema::dropIfExists('test_student_courses');
        Schema::dropIfExists('test_courses');
        Schema::dropIfExists('test_students');
    }
};