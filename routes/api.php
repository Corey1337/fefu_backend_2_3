<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('posts', PostController::class)
    ->scoped([
        'post' => 'slug'
    ])
    ->missing(function () {
        return response()->json(['message' => 'Post not found'], 404);
    });

Route::apiResource('posts.comments', CommentController::class)
    ->scoped([
        'post' => 'slug',
        'comment' => 'id'
    ])
    ->missing(function () {
        return response()->json(['message' => 'Post not found'], 404);
    });

Route::post('/sign_up', [ApiAuthController::class, 'sign_up']);

Route::post('/sign_in', [ApiAuthController::class, 'sign_in']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/profile', [ApiAuthController::class, 'profile']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);
});