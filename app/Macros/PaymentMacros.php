<?php

namespace App\Macros;

use Illuminate\Database\Eloquent\Builder;

class PaymentMacros
{
    /**
     * Register all payment-related macros
     */
    public static function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | active
        |--------------------------------------------------------------------------
        */
        Builder::macro('active', function () {
            return $this->where('is_deleted', '<>', 1);
        });

        /*
        |--------------------------------------------------------------------------
        | notExists
        |--------------------------------------------------------------------------
        | Returns true if no records exist
        |--------------------------------------------------------------------------
        */
        Builder::macro('notExists', function () {
            return !$this->exists();
        });

        /*
        |--------------------------------------------------------------------------
        | dateFilter
        |--------------------------------------------------------------------------
        */
        Builder::macro('dateFilter', function ($startDate, $endDate, $date = "payment_date") {
            return $this->whereBetween($date, [$startDate, $endDate]);
        });

        /*
        |--------------------------------------------------------------------------
        | netAmount
        |--------------------------------------------------------------------------
        */
        Builder::macro('netAmount', function () {
            return $this->selectRaw("
                COALESCE(
                    SUM(
                        CASE 
                            WHEN type = 'refunded' THEN -paid_amount
                            ELSE paid_amount
                        END
                    ), 0
                ) AS amount
            ");
        });

        /*
        |--------------------------------------------------------------------------
        | totalPaid
        |--------------------------------------------------------------------------
        */
        Builder::macro('totalPaid', function () {
            return $this->where('is_deleted', 0)
                        ->netAmount()
                        ->value('amount');
        });
    }
}