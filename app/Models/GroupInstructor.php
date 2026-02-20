<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupInstructor extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'group_instructors';

    protected $fillable = [
        'group_id',
        'instructor_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id')
                    ->instructor();
    }
}