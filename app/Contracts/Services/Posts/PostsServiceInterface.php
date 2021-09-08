<?php

namespace App\Contracts\Services\Posts;
use Illuminate\Http\Request;
use App\Models\Post;
interface PostsServiceInterface
{
  public function getPostsList();
  public function setPostsList(Request $request);
  public function updatePostsList(Request $request, Post $posts);
  public function deletePostsList(Post $posts);
  public function uploadData(Request $request, int $userId);
}
