<?php

namespace App\Http\Controllers;

use App\Models\QuizSession;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuizController extends Controller
{
    //
    public function showSession(Request $request, $code)
    {
        $quizSession = QuizSession::where('code', $code)->first();
        if (!$quizSession) {
            throw new NotFoundHttpException('Quiz session not found');
        }
        return Inertia::render('quiz', [
            'session' => $quizSession,
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
}
