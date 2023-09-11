<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $videoId;
    public $userId;



    /**
     * Create a new event instance.
     *
     * @param  int  $videoId
     * @param  int  $userId
     * @return void
     */

    /**
     * Create a new event instance.
     */
    public function __construct($videoId, $userId)
    {
        $this->videoId = $videoId;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

}







