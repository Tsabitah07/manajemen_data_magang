<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampusUnit extends Model
{
    protected $fillable = [
        'user_id',
        'unit_name',
        'position',
    ];
}
