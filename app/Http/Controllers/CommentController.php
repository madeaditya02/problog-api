<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(Post $post, Request $request)
    {
        return $post->comments()->whereNull('comment_id')->with(['user:id,name,username,picture', 'likes'])->withCount('replies')->latest()->get()->map(function($val) {
            $val->liked = auth()->check() ? $val->likes->contains(fn($value) => $value->user_id == auth()->id()) : false;
            $val->commented_at = $val->created_at->diffForHumans();
            return $val;
        })->toJson();
    }
    public function comment(Request $request)
    {
        return json_encode(DB::table('comments')->insertGetId([
            'user_id' => auth()->id(),
            'post_id' => $request->postId,
            'comment_id' => $request->commentId ?? null,
            'comment' => $request->comment,
            'created_at' => now(),
            'updated_at' => now(),
        ]));
    }
    public function replies(Comment $comment, Request $request)
    {
        return $comment->replies()->with(['user:id,name,picture', 'likes'])->latest()->get()->map(function($val) {
            $val->commented_at = $val->created_at->diffForHumans();
            $val->liked = auth()->check() ? $val->likes->contains(fn($value) => $value->user_id == auth()->id()) : false;
            return $val;
        })->toJson();
    }
    public function getLikes($id, Request $request)
    {
        // return $comment->likes()->with('user:id,name,username,picture')->get()->pluck('user');
        return DB::table('likes')
        ->join('users', 'users.id', '=', 'likes.user_id')
        ->select('users.id', 'users.name', 'users.username', 'users.picture')
        ->where('likes.likeable_id', $id)
        ->where('likes.likeable_type', Comment::class)
        ->get();
    }
    public function like($id, Request $request)
    {
        $user_id = auth()->id();
        $like = json_decode($request->like);
        if ($like) {
            DB::table('likes')->updateOrInsert([
                'user_id' => $user_id,
                'likeable_id' => $id,
                'likeable_type' => Comment::class,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('likes')->where('user_id', $user_id)->where('likeable_id', $id)->where('likeable_type', Comment::class)->delete();
        }
    }
}
