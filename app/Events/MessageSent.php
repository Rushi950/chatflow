<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    
    public function __construct(Message $message)
    {
        $this->message=$message;
    }

    
     //@return array<int, Channel>
     

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' .$this->message->conversation_id),
        ];
    }

    public function broadcastWith(){
        return[
            'message'=>[
                'id'=>$this->message->id,
                'body'=>$this->message->body,
                'user_id'=>$this->message->user_id,
                'created_at'=>$this->message->created_at->format('H:i'),
                'user'=>[
                    'id'=>$this->message->user->id,
                    'name'=>$this->message->user->name
                ],
            ]
        ];
    }
}
