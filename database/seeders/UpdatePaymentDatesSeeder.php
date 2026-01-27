<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnrolledCourse;

class UpdatePaymentDatesSeeder extends Seeder
{
    public function run()
    {
        EnrolledCourse::with('payments')
            ->whereNotNull('admission_date')
            ->chunkById(100, function ($courses) {

                foreach ($courses as $course) {

                    if ($course->payments->isEmpty()) {
                        continue;
                    }

                    $course->payments()
                        ->whereNull('payment_date') // optional safety
                        ->update([
                            'payment_date' => $course->admission_date,
                            "payment_method" => "cash"
                        ]);
                }
            });

        $this->command->info('Payment dates updated successfully from admission_date.');
    }
}
