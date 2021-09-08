<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $posts = DB::table('posts')
        //     ->join('users as u1', 'u1.id', '=', 'posts.create_user_id')
        //     ->join('users as u2', 'u2.id', '=', 'posts.updated_user_id')
        //     ->select('posts.*')
        //     ->where('posts.status', '=', '1')
        //     ->latest()
        //     ->paginate(5);
        log::info('Postsssssss');
        log::info($this->user);
        return $this->user
            ->posts()
            ->get();
        // return $posts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only('title', 'description');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'description' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $post = DB::table('posts')->insert([
            'title' => $request->title, 'description' => $request->description, 'status' => '1',
            'create_user_id' => 1,
            'updated_user_id' => 1
        ]);
        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = $this->user->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product not found.'
            ], 400);
        }

        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
        $data = $request->only('title', 'description');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'description' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, update product
        $post = $post->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $post
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ], Response::HTTP_OK);
    }
}
