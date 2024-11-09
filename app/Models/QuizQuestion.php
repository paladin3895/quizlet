<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    //
    protected $fillable = [
        'answer',
        'options',
        'flag',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
