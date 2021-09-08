<?php

namespace App\Services\Posts;

use App\Contracts\Dao\Posts\PostsDaoInterface;
use App\Contracts\Services\Posts\PostsServiceInterface;
use Illuminate\Http\Request;
use App\Models\Post;
class PostsService implements PostsServiceInterface
{
  private $postsDao;

  /**
   * Class Constructor
   * @param OperatorUserDaoInterface
   * @return
   */
  public function __construct(PostsDaoInterface $postsDao)
  {
    $this->postsDao = $postsDao;
  }

  /**
   * Get User List
   * @param Object
   * @return $userList
   */

  public function getPostsList()
  {
    return $this->postsDao->getPostsList();
  }

  public function setPostsList(Request $request) {
     return $this->postsDao->setPostsList($request);
  }
  public function updatePostsList(Request $request, int $postId){
    return $this->postsDao->updatePostsList($request,$postId);
  }
  public function deletePostsList(int $id) {
    return $this->postsDao->deletePostsList($id);
  }
  public function showSelectedPost(int $id){
    return $this->postsDao->showSelectedPost($id);
  }
  
}
