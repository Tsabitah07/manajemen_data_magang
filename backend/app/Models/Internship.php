<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'description',
        'start_date',
        'end_date',
        'quota',
        'documentation',
        'campus_unit_id',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function campusUnit()
    {
        return $this->belongsTo(CampusUnit::class);
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
        return 'IN-'.date('YmdHis').'-'.rand(1000, 9999);
    }
}
