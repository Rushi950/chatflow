<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ConversationResource;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;

class MessageController extends Controller
{
    public function index(Conversation $conversation){
        $this->authorize('view',$conversation);
        $messages=$conversation->messages()->with('user','conversation')->oldest()->get();
        return Inertia::render('Chat/Show',[
            'conversation' => (new ConversationResource($conversation))->resolve(),
            'messages'=>MessageResource::collection($messages)
        ]);
    }

    public function store(StoreMessageRequest $request, Conversation $conversation){
        $this->authorize('view',$conversation);
        $message=$conversation->messages()->create(['user_id'=>auth()->id(),...$request->validated()]);

        $message->load('user');

        broadcast(new MessageSent($message))->toOthers();

        return redirect()->back();
    }

    public function update(UpdateMessageRequest $request, Message $message){
       if(auth()->id() !== $message->user_id){
        abort(403);
       }
        $message->update($request->validated());
        return redirect()->back();
    }

    public function destroy(Message $message){
        if(auth()->id() !== $message->user_id){
        abort(403);
        }
        $message->delete();
        return redirect()->back();
    }
}