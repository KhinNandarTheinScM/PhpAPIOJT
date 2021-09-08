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
use App\Contracts\Services\Posts\PostsServiceInterface;

class PostController extends Controller
{
    private $postsInterface;
    protected $user;


    public function __construct(PostsServiceInterface $postsInterface)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->postsInterface = $postsInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->postsInterface->getPostsList();
        return $posts;
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
        log::info('Welcome from Store');
        log::info($request);
        $data = $request->only('title', 'comment');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $posts = $this->postsInterface->setPostsList($request);
        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'title' => $request->title,
            'comment' => $request->comment,
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
        $post = $this->postsInterface->showSelectedPost($id);
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, User not found.',

            ], 400);
        } else {
            return response()->json([
                'success' => true,
                "id" => $post->id,
                "title" => $post->title,
                "comment" => $post->comment,
                "status" => $post->status,
                "created_user_id" => $post->create_user_id
            ], Response::HTTP_OK);
        }
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
    public function update(Request $request, int $id)
    {
        //
        $data = $request->only('title', 'comment', 'status');
        $validator = Validator::make($data, [
            'title' => 'required|string',
            'comment' => 'required',
            'status' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $posts = $this->postsInterface->updatePostsList($request, $id);

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => $request->status
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, $id)
    {
        $post = $this->postsInterface->deletePostsList($id);
        log::info('Post Delete');
        log::info($post);
        // $id = Post::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
            'deleted_user_id' =>  '1',
            'deleted_at' => '2021-09-08 06:00:00',
        ], Response::HTTP_OK);
    }
}
