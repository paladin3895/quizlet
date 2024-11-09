<?php

namespace App\Events;

use App\Models\QuizQuestion;
use App\Models\QuizSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizQuestionCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $session;

    public $question;

    /**
     * Create a new event instance.
     */
    public function __construct(QuizSession $session, QuizQuestion $question)
    {
        $this->session = $session;
        $this->question = $question;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(sprintf('App.Models.QuizSession.%s', $this->session->id)),
        ];
    }
}
