<?php

namespace App\Contracts\Dao\Posts;
use Illuminate\Http\Request;
use App\Models\Post;
interface PostsDaoInterface
{
  public function getPostsList();
  public function setPostsList(Request $request);
  public function updatePostsList(Request $request, Post $posts);
  public function deletePostsList(Post $posts);
  public function uploadData(Request $request, int $userId);
}
