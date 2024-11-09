<?php

namespace App\Http\Controllers;

use App\Events\QuizQuestionCreated;
use App\Events\QuizResultsUpdated;
use App\Models\QuizCountry;
use App\Models\QuizSession;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Nubs\RandomNameGenerator\Alliteration;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuizController extends Controller
{
    protected User $user;

    //
    public function __construct()
    {
        if (Session::get('user_id')) {
            $this->user = User::find(Session::get('user_id'));
        } else {
            $generator = new Alliteration();
            $this->user = User::create([
                'name' => $generator->getName(),
                'email' => uniqid('', true),
                'password' => '',
            ]);
            Session::put('user_id', $this->user->id);
        }
    }

    public function showSession(Request $request, $code)
    {
        $quizSession = QuizSession::where('code', $code)->first();
        if (!$quizSession) {
            throw new NotFoundHttpException('Quiz session not found');
        }
        $quizResult = $this->user->results()->where('quiz_session_id', $quizSession->id)->first();
        if (!$quizResult) {
            $this->user->results()->create([
                'quiz_session_id' => $quizSession->id,
                'score' => 0,
            ]);
        }
        return Inertia::render('quiz', [
            'session' => $quizSession,
            'user' => $this->user,
            'results' => $quizSession->getUserResults(),
        ]);
    }

    public function findSession(Request $request)
    {
        $sessionCode = $request->get('code');
        $quizSession = QuizSession::where('code', $sessionCode)->first();
        if (!$quizSession) {
            throw new NotFoundHttpException('Quiz session not found');
        }
        return $quizSession;
    }

    public function createSession(Request $request)
    {
        try {
            return QuizSession::create([
                'name' => $request->post('name'),
                'code' => uniqid(),
            ]);
        } catch (Exception $e) {
            throw new ConflictHttpException('Cannot create Quiz, please try again!');
        }
    }

    public function generateQuestion(Request $request, $code)
    {
        $quizSession = QuizSession::where('code', $code)->first();
        if (!$quizSession) {
            throw new NotFoundHttpException('Quiz session not found');
        }
        if ($quizSession->questions()->count() >= 20) {
            throw new NotFoundHttpException('Quiz session has finished, thank you for joining!');
        }
        $currentQuestion = $quizSession->questions()->orderBy('id', 'desc')->first();
        // check if question has already generated or not?
        if ($currentQuestion?->id == $request->post('current_question_id')) {
            $question = $quizSession->generateQuestion();
            broadcast(new QuizQuestionCreated($quizSession, $question));
            return $question;
        } else {
            return $currentQuestion;
        }
    }

    public function validateAnswer(Request $request, $code)
    {
        $quizSession = QuizSession::where('code', $code)->first();
        if (!$quizSession) {
            throw new NotFoundHttpException('Quiz session not found');
        }
        $currentQuestion = $quizSession->questions()->where('id', $request->post('question_id'))->first();
        if (!$currentQuestion) {
            throw new NotFoundHttpException('Question not found');
        }
        $result = $this->user->results()->where('quiz_session_id', $quizSession->id)->first();
        if ($currentQuestion->answer == $request->post('answer')) {
            $result->update([
                'score' => $result->score + 1,
            ]);
            broadcast(new QuizResultsUpdated($quizSession));
            return $result;
        }
        throw new BadRequestHttpException('Your answer is not correct');
    }

    public function getCountryFlag($country)
    {
        $country = QuizCountry::where('code', $country)->first();
        return response($country->flag ?? '')->header('Content-Type', 'image/svg+xml');
    }
}
