<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;

class ConversationTest extends TestCase
{
    
    use RefreshDatabase;

    public function test_user_can_create_conversation(): void
    {

        $user=User::factory()->create();
        $conversation=Conversation::create([
            'name'=>'my chat'
        ]);

        $response = $this->actingAs($user)->post("/conversations",['name'=>'my chat','is_group' => false]);

        $response->assertRedirect();
        $this->assertDatabaseHas('conversations',[
            'name'=>'my chat'
        ]);
    }
}
