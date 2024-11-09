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

    public $hidden = [
        'flag',
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
