<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $writers = User::select(['id', 'name', 'username', 'picture'])->withCount('followers')->with('followers')->where('name', 'like', "%$request->q%")->orWhere('username', 'like', "%$request->q%")->get()->map(function($user) {
            $user->followed = $user->followed();
            return $user;
        });
        return response()->json($writers);
    }
    public function popular(Request $request)
    {
        $writers = User::has('posts')->select(['id', 'name', 'username', 'picture'])->withCount('followers')->with('followers')->orderBy('followers_count', 'desc')->limit(4)->get()->map(function($user) {
            $user->followed = $user->followed();
            return $user;
        });
        return response()->json($writers);
    }
}
