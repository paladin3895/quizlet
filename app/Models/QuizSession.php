<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizSession extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function generateQuestion()
    {
        $question = [
            'flag' => null,
            'options' => [],
            'answer' => null,
        ];
        $randomIds = QuizCountry::pluck('id')->shuffle()->pop(4);
        QuizCountry::whereIn('id', $randomIds)
            ->get()
            ->each(function (QuizCountry $country) use (&$question, $randomIds) {
                $question['options'][$country->code] = $country->name;
                // pick country flag and answer randomly
                if ($country->id == $randomIds->first()) {
                    $question['flag'] = $country->flag;
                    $question['answer'] = $country->code;
                }
            });
        return $this->questions()->create($question);
    }
}
