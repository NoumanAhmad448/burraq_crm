<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnrolledCourse;
use App\Models\EnrolledCoursePayment;

class UpdatePaymentDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $enrolledCourses = EnrolledCourse::with('payments')->get();

        foreach ($enrolledCourses as $course) {
            if ($course->admission_date) {
                $paymentIds = $course->payments()->pluck('id');
                EnrolledCoursePayment::whereIn('id', $paymentIds)
                    ->update(['payment_date' => $course->admission_date, "payment_method" => "cash"]);
            }
        }

        $this->command->info('All payment dates updated successfully from admission_date.');
    }
}
