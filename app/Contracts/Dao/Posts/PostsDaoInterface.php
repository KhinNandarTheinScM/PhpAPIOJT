<?php

namespace App\Contracts\Dao\Posts;
use Illuminate\Http\Request;
use App\Models\Post;
interface PostsDaoInterface
{
  public function getPostsList();
  public function setPostsList(Request $request);
  public function updatePostsList(Request $request,int $postId);
  public function deletePostsList(int $id);
  public function showSelectedPost(int $id);
 }
