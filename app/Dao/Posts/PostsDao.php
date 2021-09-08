<?php

namespace App\Dao\Posts;

// use DB;
use Illuminate\Support\Facades\DB;
use App\Contracts\Dao\Posts\PostsDaoInterface;
use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsDao implements PostsDaoInterface
{

  //user list action
  public function getPostsList()
  {
    $posts = DB::table('posts')->select('posts.*')
                ->join('users as u1', 'u1.id', '=', 'posts.create_user_id')
                ->select('posts.id','posts.title','posts.comment','posts.created_at','u1.name as createdUser')
                ->where('posts.status', '=', '1')
                ->get();
    return $posts;
  }

  public function setPostsList(Request $request)
  {
    $post = DB::table('posts')->insert([
      'title' => $request->title, 'comment' => $request->comment,
  ]);
    return $post;
  }
  public function updatePostsList(Request $request, int $postId)
  {
    $post = DB::table('posts')
        ->where('posts.id', '=',$postId)
        ->update(['title' => $request->title, 'comment' => $request->comment, 'status' => $request->status]);
    return $post;
  }
  public function deletePostsList(int $id)
  {
    $posts = DB::table('posts')
      ->where('posts.id', '=',$id)
      ->update(['status' => '0', 'deleted_user_id' => 1]);
    return $posts;
  }
  public function showSelectedPost(int $id){
    $post = Post::find($id);
    return $post;
  }
}
