<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentFeeReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;

    public function __construct($student)
    {
        // dd($student);
        $this->student = $student;
    }

    public function build()
    {
        // dd($this->student);
        return $this->subject('Student Enrollment & Fee Receipt')
            ->view('emails.student_fee_receipt');
    }
}
