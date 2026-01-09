<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request)
    {
        Payment::create($request->only([
            'enrolled_course_id',
            'amount',
            'payment_date',
            'method',
            'note'
        ]));

        return back()->with('success', 'Payment added');
    }
}
