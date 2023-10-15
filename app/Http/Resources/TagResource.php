<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'tag' => $this->tag,
            'posts_count' => $this->whenCounted('posts'),
            'followed' => $this->whenLoaded('followers', $this->followers->contains(fn ($val) => $val->user_id == auth()->id())),
            'followers_count' => $this->whenCounted('followers')
        ];
    }
}
