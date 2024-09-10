<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChosenCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_1',
        'course_2',
        'course_3',
    ];
}
