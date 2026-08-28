<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Throwable;

class ErrorOccurred
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $exception;
    public function __construct(Throwable $exception)
    { 
        $this->exception = $exception;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // $exception = $this->exception ;
        // // return new PrivateChannel('channel-name');
        // Log::info("############################ ERORRRRRRRRRR ".$exception->getMessage());
        // \Log::error('###################################'.$exception->getMessage());
        // \Log::error('###################################URL'.request()->url());
       
    }
}
