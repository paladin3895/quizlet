<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/quiz/flag/{country}', [QuizController::class, 'getCountryFlag']);
Route::get('/quiz', [QuizController::class, 'findSession']);
Route::post('/quiz', [QuizController::class, 'createSession']);
Route::get('/quiz/{code}', [QuizController::class, 'showSession'])->name('quiz');
Route::post('/quiz/{code}/question', [QuizController::class, 'generateQuestion']);
Route::post('/quiz/{code}/answer', [QuizController::class, 'validateAnswer']);
