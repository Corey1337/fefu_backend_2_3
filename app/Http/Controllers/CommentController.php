<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    private const PAGE_SIZE = 5;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Post $post): JsonResponse
    {
        return response()->json(CommentResource::collection($post->comments()->with('user')->ordered()->paginate(self::PAGE_SIZE)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|max:200',
         ]);
 
         if ($validator->fails())
             return response()->json(['errors' => $validator->errors()->all()], 422);

        $com = new Comment();
        $com->text = $validator->validated()['text'];
        $com->user_id = User::inRandomOrder()->first()->id;
        $com->post_id = $post->id;
        $com->save();

        return response()->json(new CommentResource($com), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $com, Post $post): JsonResponse
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
    public function update(Request $request, Comment $com, Post $post): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|max:200',
         ]);
 
         if ($validator->fails())
             return response()->json(['errors' => $validator->errors()->all()], 422);


        $com->text = $validator->validated()['text'];
        $com->save();

        return response()->json(new CommentResource($com), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $com, Post $post): JsonResponse
    {
       $com->delete();
       return response()->json(['message' => 'Comment removed successfully']);
    }
}
