<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::put('update_user/{user}',  [ApiController::class, 'update_user']);
    Route::post('create_user', [ApiController::class, 'create_user']);
    Route::delete('delete_user/{id}', [ApiController::class, 'delete_user']);
    Route::get('user/{id}', [ApiController::class, 'show']);
    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{id}', [PostController::class, 'show']);
    Route::post('create', [PostController::class, 'store']);
    Route::put('update/{product}',  [PostController::class, 'update']);
    Route::delete('delete/{product}',  [PostController::class, 'destroy']);
});