<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'is_group'=>$this->is_group,
            'users' => $this->users->map(fn($u) => ['id' => $u->id, 'name' => $u->name]),
            'latest_message' => $this->latestMessage ? [
                'body' => $this->latestMessage->body,
                'created_at' => $this->latestMessage->created_at->diffForHumans(),
            ] : null,
        ];
    }
}
