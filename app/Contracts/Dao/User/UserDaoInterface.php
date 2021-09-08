<?php

namespace App\Contracts\Dao\User;
use Illuminate\Http\Request;
use App\Models\User;
interface UserDaoInterface
{
  public function getUserList(Request $request);
  public function setUsersList(Request $request);
  public function updateUserProfile(Request $request, User $user);
  public function deleteUserList(int $userId);
  public function showSelectedUser(int $userId);
}
