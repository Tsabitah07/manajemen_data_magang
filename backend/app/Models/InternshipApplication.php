<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipApplication extends Model
{
    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'internship_id',
        'student_id',
        'cv',
        'cv_required',
        'status',
    ];

    public function internship()
    {
        return $this->belongsTo(Internship::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = self::generateUniqueId();
        });
    }

    public static function generateUniqueId()
    {
        return 'IA-'.date('YmdHis').'-'.rand(1000, 9999);
    }
}
