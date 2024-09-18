<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_id',
        'fullname',
        'gender',
        'age',
        'birthday',
        'strand',
        'course_1',
        'course_2',
        'course_3',
        'total_points',
        'number_of_items',
    ];
}
