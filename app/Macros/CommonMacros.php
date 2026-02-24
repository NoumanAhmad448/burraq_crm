<?php

namespace App\Macros;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;


class CommonMacros
{
    public static function register()
    {
        /*
        |--------------------------------------------------------------------------
        | whereNotDeleted
        |--------------------------------------------------------------------------
        | Works for tables using `is_deleted = 0`
        */
        EloquentBuilder::macro('whereNotDeleted', function () {
            return $this->where('is_deleted', 0);
        });

        QueryBuilder::macro('whereNotDeleted', function () {
            return $this->where('is_deleted', 0);
        });
        EloquentBuilder::macro('whereNotSoftDeleted', function () {
            return $this->whereNull('deleted_at');
        });

        QueryBuilder::macro('whereNotSoftDeleted', function () {
            return $this->whereNull('deleted_at');
        });

        /*
        |--------------------------------------------------------------------------
        | dateBetween
        |--------------------------------------------------------------------------
        */
        EloquentBuilder::macro('dateBetween', function ($column, $start, $end) {
            return $this->whereBetween($column, [$start, $end]);
        });

        QueryBuilder::macro('dateBetween', function ($column, $start, $end) {
            return $this->whereBetween($column, [$start, $end]);
        });
    }
}
