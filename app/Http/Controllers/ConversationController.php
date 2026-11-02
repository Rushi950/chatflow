<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Conversation;
use App\Http\Resources\ConversationResource;
use App\Http\Requests\StoreConversationRequest;

class ConversationController extends Controller
{
    public function index(){
        $conversations=auth()->user()->conversations()->with(['users','latestMessage'])->latest()->get();
        return Inertia::render('Chat/Index',[
            'conversations'=>ConversationResource::collection($conversations)
        ]);
    }

    public function store(StoreConversationRequest $request){
        $this->authorize('create',Conversation::class);
        $conversation=Conversation::create($request->validated());
        $conversation->users()->attach(auth()->id());
        return redirect()->route('conversations.show',$conversation->id);
    }
}
