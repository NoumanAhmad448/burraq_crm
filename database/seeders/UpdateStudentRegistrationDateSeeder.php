<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnrolledCourse;
use App\Models\Student as CRMStudent;

class UpdateStudentRegistrationDateSeeder extends Seeder
{
    public function run()
    {
        // Fetch all active enrolled courses
        $enrolledCourses = EnrolledCourse::where('is_deleted', 0)->get();

        foreach ($enrolledCourses as $course) {
            if ($course->student && $course->admission_date) {
                CRMStudent::where('id', $course->student_id)
                    ->update([
                        'registration_date' => $course->admission_date
                    ]);
            }
        }

        $this->command->info('Student registration_date updated from EnrolledCourse.');
    }
}
