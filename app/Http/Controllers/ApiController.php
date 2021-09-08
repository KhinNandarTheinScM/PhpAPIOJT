<?php

namespace App\Http\Controllers;

use App\Contracts\Services\User\UserServiceInterface;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Hash;

class ApiController extends Controller
{
    private $userInterface;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserServiceInterface $userInterface)
    {
        // $this->middleware('visitors');
        $this->userInterface = $userInterface;
    }
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
        $user = $this->userInterface->getUserList($request);
        return response()->json(['user' => $user]);
    }
    public function delete_user($id)
    {
        $user = $this->userInterface->deleteUserList($id);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
    public function create_user(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation', 'profile_photo', 'type', 'phone', 'address', 'date_of_birth');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password'         => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'profile_photo' => 'required|string',
            'type' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new product
        $user = $this->userInterface->setUsersList($request);

        //Product created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' =>  $user
        ], Response::HTTP_OK);
    }
    public function update_user(Request $request, User $user)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation', 'profile_photo', 'type', 'phone', 'address', 'date_of_birth');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|unique:users,email',
            'password'         => 'required|min:8',
            'password_confirmation' => 'required|same:password',
            'profile_photo' => 'required|string',
            'type' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'date_of_birth' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = $this->userInterface->updateUserProfile($request, $user);
        //Request is valid, update product

        //Product updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }
    public function show($id)
    {
        Log::info('Welcome from show');
        log::info($id);
        $user = $this->userInterface->showSelectedUser($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, User not found.'
            ], 400);
        }

        return $user;
    }
}
