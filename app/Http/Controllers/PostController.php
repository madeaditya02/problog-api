<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\SimplePostResource;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return SimplePostResource::collection(Post::where('user_id', $request->user()->id)->latest()->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            // 'slug' => ['unique:posts'],
            'body' => ['required'],
        ]);
        $data = $request->all();
        $exist = Post::where('slug', $data['slug'])->count();
        if (!$data['slug'] || $data['slug'] == '' || $exist) {
            $data['slug'] = SlugService::createSlug(Post::class, 'slug', $data['title']);
        }
        if ($request->file('imageCoverFile')) {
            $extension = $request->file('imageCoverFile')->getClientOriginalExtension();
            $request->file('imageCoverFile')->storeAs('public/cover', $data['slug'].'.'.$extension);
            $data['imageCover'] = asset("storage/cover/".$data['slug'].'.'.$extension);
        }
        $post = Post::create([
            'title' => $data['title'],
            'user_id' => $request->user()->id,
            'slug' => $data['slug'],
            'body' => $data['body'],
            'image_cover' => $data['imageCover']
        ]);
        if ($data['tags'] && count($data['tags']) > 0) {
            $data['tags'] = collect($data['tags'])->pluck('id')->unique();
            $post->tags()->sync($data['tags']);
        }
        return response()->json([
            'post' => $post,
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Post::with('tags')->find($id));
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        $data = $request->all();
        $request->validate([
            'title' => ['required'],
            'body' => ['required'],
        ]);
        $data = $request->all();
        $exist = Post::where('slug', $data['slug'])->first();
        $exist = $exist && ($exist->id != $post->id);
        if (!$data['slug'] || $data['slug'] == '' || $exist) {
            $data['slug'] = SlugService::createSlug(Post::class, 'slug', $data['title']);
        }
        if ($request->file('imageCoverFile')) {
            $extension = $request->file('imageCoverFile')->getClientOriginalExtension();
            $request->file('imageCoverFile')->storeAs('public/cover', $data['slug'].'.'.$extension);
            $data['imageCover'] = asset("storage/cover/".$data['slug'].'.'.$extension);
        }
        $post->title = $data['title'];
        $post->slug = $data['slug'];
        $post->body = $data['body'];
        $post->image_cover = $data['imageCover'];
        if ($data['tags'] && count($data['tags']) > 0) {
            $data['tags'] = collect($data['tags'])->pluck('id')->unique();
            $post->tags()->sync($data['tags']);
        }
        else {
            $post->tags()->detach();
        }
        $post->save();
        return response()->json(['post' => $post]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Post::find($id)->delete();
    }

    public function bookmarks(Request $request)
    {
        return PostResource::collection($request->user()->bookmarks()->with(['user:id,name,username,picture', 'likes'])->withCount('comments')->latest()->paginate(5));
    }
    
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;
    
            // $request->file('upload')->move(public_path('media'), $fileName);

            $path = $request->file('upload')->storeAs('public/temp', $fileName);
    
            // $url = asset($path);
            $url = asset('storage/temp/'.$fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }
}
