<?php

namespace public;

use Illuminate\Support\Facades\Mail;


Mail::raw('Test email', function($message){
    $message->to('nouman.laravel@outlook.com')
            ->subject('Test from Laravel');
});