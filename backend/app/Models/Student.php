<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'gender',
        'nim',
        'major',
        'status',
        'enrollment_year',
    ];
}
