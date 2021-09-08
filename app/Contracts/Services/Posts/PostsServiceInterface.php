<?php

namespace App\Contracts\Services\Posts;
use Illuminate\Http\Request;
use App\Models\Post;
interface PostsServiceInterface
{
  public function getPostsList();
  public function setPostsList(Request $request);
  public function updatePostsList(Request $request,int $postId);
  public function deletePostsList(int $id);
  public function showSelectedPost(int $id);
}
