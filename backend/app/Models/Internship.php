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
        'is_open',
        'cv_required',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function campusUnit()
    {
        return $this->belongsTo(User::class, 'campus_unit_id');
    }

    protected function casts(): array
    {
        return [
            'is_open' => 'boolean',
            'cv_required' => 'boolean',
        ];
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
