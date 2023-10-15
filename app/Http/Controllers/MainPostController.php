<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use App\Http\Resources\SinglePostResource;

class MainPostController extends Controller
{
    public function index()
    {
        return PostResource::collection(Post::with(['user', 'bookmarks'])->withCount(['likes', 'comments'])->latest()->paginate(5));
    }
    public function show(Post $post)
    {
        return new SinglePostResource($post->load(['likes', 'user', 'tags'])->loadCount('comments'));
    }
    public function likes(Post $post)
    {
        return $post->likes()->with(['user:id,username,name,picture', 'user.followers'])->get();
    }
    public function likePost(Post $post, Request $request)
    {
        // return response()->json(['like' => $request->like, 'userId'=> $request->userId]);
        $like = json_decode($request->like);
        if ($like) {
            DB::table('likes')->updateOrInsert([
                'user_id' => $request->user()->id,
                'likeable_id' => $post->id,
                'likeable_type' => Post::class,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // $post->likes()->updateOrCreate(['user_id', $request->userId]);
        } else {
            $post->likes()->where('user_id', $request->userId)->delete();
            // DB::table('likes')->where('user_id', $request->userId)->where('likeable_id', $post->id)->where('likeable_type', Post::class)->delete();
        }
        return response()->json(['success' => true]);
    }
    public function bookmarkPost(Post $post, Request $request)
    {
        $user_id = $request->user()->id;
        $bookmark = json_decode($request->bookmark);
        if ($bookmark) {
            DB::table('bookmarks')->updateOrInsert([
                'user_id' => $user_id,
                'post_id' => $post->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('bookmarks')->where('user_id', $user_id)->where('post_id', $post->id)->delete();
        }
    }
}
