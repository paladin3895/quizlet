<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizCountry extends Model
{
    protected $fillable = [
        'name',
        'code',
        'flag',
    ];
}
