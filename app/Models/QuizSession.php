<?php

namespace App\Models;

use App\Events\QuizQuestionCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizSession extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quiz_results');
    }

    public function questions(): HasMany
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
        $question = $this->questions()->create($question);
        return $question;
    }

    public function getUserResults()
    {
        $users = $this->users()->withPivot(['score'])->get();
        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'score' => $user->getOriginal('pivot_score'),
            ];
        }
        return $results;
    }
}
