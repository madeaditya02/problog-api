<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $auth_id = $request->user()->id ?? null;
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => Str::words($this->body, $request->bodyLength ?? 25),
            'image' => $this->image_cover,
            'created_at' => $this->created_at,
            // 'published_at' => $request->route()->getName() == 'view-post' ? $this->created_at->format('M j, Y') : $this->created_at->diffForHumans(),
            // 'published_at' => [$this->created_at->diffForHumans(), $this->created_at->format('M j, Y'), $this->created_at->format('j M, Y')],
            'likes' => $this->whenLoaded('likes', $this->likes),
            // 'likes' => $this->whenLoaded('likes', $this->likes->count()),
            'liked' => $this->whenLoaded('likes', $this->likes->contains(fn($value, $key) => $value->user_id == $auth_id)),
            'bookmarked' => $this->whenLoaded('bookmarks', $this->bookmarks->contains(fn($value) => $value->user_id == $auth_id)),
            // 'bookmarked' => $this->whenLoaded('bookmarks', $bookmarked),
            // 'bookmarked' => $request->is('/bookmarks') ? true : ($this->whenLoaded('bookmarks', $logged ? $this->bookmarks->contains(fn($value) => $value->user_id == $auth_id) : false)),
            'comments' => $this->whenCounted('comments'),
            // 'tags' => PostResource::collection($this->whenLoaded('tags')),
            // 'tags' => $this->when($request->route()->getName() == 'view-post', $this->tags->makeHidden('pivot')),
            'user' => ['name' => $this->user->name, 'username' => $this->user->username, 'picture' => $this->user->picture]
        ];
    }
}
