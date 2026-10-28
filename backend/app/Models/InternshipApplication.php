<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipApplication extends Model
{
    protected $fillable = [
        'internship_id',
        'student_id',
        'cv',
        'status',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
