<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('conversation.{conversationId}', function($user,$conversationId){
    $conversation=Conversation::find($conversationId);

    return $conversation && $conversation->users->contains($user->id);
});
