<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_message(): void
    {

        $user=User::factory()->create();
        $conversation=Conversation::create([
            'name'=>'my chat'
        ]);

        $conversation->users()->attach($user->id);

        $response = $this->actingAs($user)->post("/conversations/{$conversation->id}/messages",[
            'body'=>'this is message body'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('messages',[
          'body'=>'this is message body',
          'user_id'=>$user->id,
          'conversation_id'=>$conversation->id
        ]);
    }
}
