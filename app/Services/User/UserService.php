<?php

namespace App\Services\User;

use App\Contracts\Dao\User\UserDaoInterface;
use App\Contracts\Services\User\UserServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

class UserService implements UserServiceInterface
{
  private $userDao;

  /**
   * Class Constructor
   * @param OperatorUserDaoInterface
   * @return
   */
  public function __construct(UserDaoInterface $userDao)
  {
    $this->userDao = $userDao;
  }

  /**
   * Get User List
   * @param Object
   * @return $userList
   */

  public function getUserList(Request $request)
  {
    return $this->userDao->getUserList($request);
  }
  public function setUsersList(Request $request)
  {
    return $this->userDao->setUsersList($request);
  }
  public function updateUserProfile(Request $request, User $user)
  {
    return $this->userDao->updateUserProfile($request, $user);
  }
  public function deleteUserList(int $userId)
  {
    return $this->userDao->deleteUserList($userId);
  }
  public function showSelectedUser(int $userId)
  {
    return $this->userDao->showSelectedUser($userId);
  }
}
