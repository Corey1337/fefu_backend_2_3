<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use function response;

class PostController extends Controller
{
    private const PAGE_SIZE = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        //$posts = Post::with('user')->ordered()->paginate(self::PAGE_SIZE);
        return response()->json(PostResource::collection(Post::with('user', 'comments')->ordered()->paginate(self::PAGE_SIZE)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'text' => 'required|max:2000',
        ]);

        if(!(isset($validated['title']) || isset($validated['text'])))
        {
            return response()->json(['message' => 'incorrect data'], 404);
        }

        $post = new Post();
        $post->title = $validated['title'];
        $post->text = $validated['text'];

        $post->user_id = User::inRandomOrder()->first()->id;
        $post->save();
        return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json(new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|max:100',
            'text' => 'required|max:2000',
        ]);

        $post = new Post();
        if(isset($validated['title']))
            $post->title = $validated['title'];
        if(isset($validated['text']))
            $post->text = $validated['text'];
        $post->save();
        return response()->json(new PostResource($post), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return response()->json(['message' => 'Post removed successfully']);
    }
}
