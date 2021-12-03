<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Post;
use App\Models\User;

class CommentController extends Controller
{
    private const PAGE_SIZE = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        return response()->json(CommentResource::collection(POST::comments()->with('user')->ordered()->paginate(self::PAGE_SIZE)));
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
            'text' => 'required|max:200',
        ]);

        if(!isset($validated['text']))
        {
            return response()->json(['message' => 'incorrect data'], 404);
        }

        $com = new Comment();
        $com->text = $validated['text'];
        $com->user_id = User::inRandomOrder()->first()->id;
        $com->post_id = Post::inRandomOrder()->first()->id;
        $com->save();

        return response()->json(new CommentResource($com), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $com): JsonResponse
    {
        return response()->json(new CommentResource($com));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $com): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|max:200',
        ]);

        $com = new Comment();

        if(isset($validated['text']))
        {
            $com->text = $validated['text'];
        }
        $com->save();

        return response()->json(new CommentResource($com), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $com): JsonResponse
    {
       $com->delete();
       return response()->json(['message' => 'Comment removed successfully']);
    }
}
