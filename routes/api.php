<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\PostResource;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MainPostController;
use \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Models\Post;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(MainPostController::class)->group(function () {
    Route::get('/posts', 'index');
    Route::get('/posts/{post:slug}', 'show');
    Route::get('/posts/{post}/likes', 'likes');
    Route::post('/posts/{post}/likes', 'likePost')->middleware('auth:sanctum');
    Route::post('/posts/{post}/bookmarks', 'bookmarkPost')->middleware('auth:sanctum');
});
Route::controller(TagController::class)->group(function () {
    Route::get('/tags', 'index');
    Route::get('/tags/{tag:slug}', 'show');
    Route::get('/tags/{tag:slug}/posts', 'tagPosts');
    Route::get('/tags-popular', 'popular');
});
Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/popular', 'popular');
});
Route::controller(CommentController::class)->group(function () {
    Route::get('/comments/{post}', 'index');
    Route::post('/comments', 'comment');
    Route::get('/comments/{comment}/replies', 'replies');
    Route::post('/comments/{id}/replies', 'reply');
    Route::get('/comments/{id}/likes', 'getLikes');
    Route::post('/comments/{id}/like', 'like');
});
// Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});
Route::get('/user', function (Request $request) {
    return response()->json([
        'data' => $request->user(),
    ]);
})->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::resource('/dashboard/posts', PostController::class);
    Route::get('/dashboard/bookmarks', [PostController::class, 'bookmarks']);
    Route::post('/dashboard/image', [PostController::class, 'uploadImage']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', function (Request $request) {
    return response()->json(['token' => csrf_token()]);
});
Route::post('/test', function (Request $request) {
    return response()->json($request->all());
});