<?php

namespace App\Events;

use App\Models\QuizSession;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizResultsUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $session;

    /**
     * Create a new event instance.
     */
    public function __construct(QuizSession $session)
    {
        //
        $this->session = $session;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel(sprintf('App.Models.QuizSession.%s', $this->session->id)),
        ];
    }

    public function broadcastWith(): array
    {
        return $this->session->getUserResults();
    }
}
