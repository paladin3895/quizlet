<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\QuizSession; // Adjust the namespace as necessary
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuizSessionTest extends TestCase
{
    // use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testQuizSessionCreation()
    {
        $quizSession = QuizSession::create([
            'name' => 'Test Quiz',
            'code' => 'test',
        ]);

        $this->assertDatabaseHas('quiz_sessions', [
            'id' => $quizSession->id,
            'name' => 'Test Quiz',
            'code' => 'test',
        ]);
    }

    public function testQuizSessionMakeQuestion()
    {
        /** @var QuizSession */
        $quizSession = QuizSession::create([
            'name' => 'Test Quiz',
            'code' => 'test',
        ]);

        $question = $quizSession->generateQuestion();
        $this->assertIsArray($question);
        $this->assertNotEmpty($question['flag']);
        $this->assertNotEmpty($question['answer']);
        $this->assertNotEmpty($question['options']);
    }
} 
