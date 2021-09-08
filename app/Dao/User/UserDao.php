<?php

namespace App\Dao\User;

use JWTAuth;
use DB;
use App\Contracts\Dao\User\UserDaoInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash;

class UserDao implements UserDaoInterface
{

  //user list action
  public function getUserList(Request $request)
  {
    $user = JWTAuth::authenticate($request->token);
    return $user;
  }


  public function setUsersList(Request $request)
  {
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password'         => Hash::make($request->password),
      'password_confirmation' => Hash::make($request->password_confirmation),
      'profile_photo' => $request->profile_photo,
      'type' => $request->type,
      'phone' => $request->phone,
      'address' => $request->address,
      'date_of_birth' => $request->date_of_birth,
      //  'title' => $request->title,
      //  'description' => $request->description,
    ]);
    return $user;
  }

  public function updateUserProfile(Request $request, User $user)
  {
    // $id = Auth::user()->id;
    $user = $user->update([
      'name' => $request->name,
      'email' => $request->email,
      'password'         => Hash::make($request->password),
      'password_confirmation' => Hash::make($request->password_confirmation),
      'profile_photo' => $request->profile_photo,
      'type' => $request->type,
      'phone' => $request->phone,
      'address' => $request->address,
      'date_of_birth' => $request->date_of_birth,
    ]);
    return $user;
  }
  public function  deleteUserList(int $userId)
  {
    $user = User::find($userId);
    return  $user;
  }
  public function showSelectedUser(int $userId)
  {
    $user = User::find($userId);
    return $user;
  }
}
