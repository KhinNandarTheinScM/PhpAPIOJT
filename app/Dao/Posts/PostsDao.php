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
    $user = Auth::user();
    $posts = DB::table('posts')
      ->join('users as u1', 'u1.id', '=', 'posts.create_user_id')
      ->join('users as u2', 'u2.id', '=', 'posts.updated_user_id')
      ->select('posts.*', 'u1.name as create_username', 'u2.name as updated_username')
      ->where('posts.status', '=', '1')
      ->latest()
      ->paginate(5);
    if (!Auth::user()->type == '0') {
      $posts = $posts->where('id', Auth::user()->id);
    };
    return $posts;
  }

  public function uploadData(Request $request, int $userId)
  {
    $path = 'uploads/' . $userId . '/csv';
    $name = time() . '_' . $request->file->getClientOriginalName();
    $request->file('file')->storeAs($path, $name, 'public');
    $file = $request->file('file');
    $filePath = $file->getRealPath();
    $file = fopen($filePath, 'r');
    while (($line = fgetcsv($file)) !== FALSE) {
      $rowData = implode(" ", $line);
      $row = explode(";", $rowData);
      // $status = (int)$row[2];
      $existing_title = DB::table('posts')->where('title', $row[0])->first();
      if (!$existing_title) {
        if (($row[0] == NULL) || ($row[1] == NULL)) {
        } else {
          Post::create(['title' => $row[0], 'description' => $row[1], 'status' => $userId, 'create_user_id' => $userId, 'updated_user_id' => $userId]);
        }
      }
    }
    fclose($file);
  }


  public function setPostsList(Request $request)
  {
    $id = Auth::user()->id;
    $posts = DB::table('posts')->insert([
      'title' => $request->title, 'description' => $request->description, 'status' => '1',
      'create_user_id' => $id,
      'updated_user_id' => $id
    ]);
    return $posts;
  }
  public function updatePostsList(Request $request, Post $posts)
  {
    $id = Auth::user()->id;
    $posts = DB::table('posts')
      ->where('posts.id', '=', $request->id)
      ->update(['title' => $request->title, 'description' => $request->description, 'status' => $request->status == 'on' ? '1' : '0', 'create_user_id' => $id, 'updated_user_id' => $id]);
  }
  public function deletePostsList(Post $posts)
  {
    $id = Auth::user()->id;
    $posts = DB::table('posts')
      ->where('posts.id', '=', $posts->id)
      ->update(['status' => '0', 'deleted_user_id' => $id]);
    return $posts;
  }
}
