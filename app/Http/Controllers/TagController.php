<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\TagResource;
use App\Http\Resources\PostResource;

class TagController extends Controller
{
    public function index(Request $request)
    {
        return TagResource::collection(Tag::withCount(['posts'])->with('followers')->where('slug', 'like', "%$request->q%")->orWhere('tag', "%$request->q%")->paginate($request->limit ?? 4));
    }
    public function popular(Request $request)
    {
        $tags = Tag::select(['id', 'tag', 'slug'])->withCount(['posts'])->with('followers')->orderBy('posts_count', 'desc')->paginate(4)->map(function($tag) {
            $tag->followed = $tag->followers->contains(fn ($val) => $val->user_id == auth()->id());
            return $tag;
        });
        return response()->json($tags);
    }
    public function show(Tag $tag)
    {
        return new TagResource($tag->load('followers')->loadCount(['posts', 'followers']));
    }
    public function tagPosts(Tag $tag)
    {
        return PostResource::collection($tag->posts()->with('user')->withCount(['likes', 'comments'])->paginate(5));
    }
}
