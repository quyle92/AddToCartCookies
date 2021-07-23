<?php

use Illuminate\Http\Request;

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

Route::post('login', 'AuthController@login');
Route::group([ 'middleware' => 'custom.jwt' ], function () {
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
});


Route::get('/posts/PostsWhoesMembersCommentFrom20To30', [App\Http\Controllers\Admin\API\PostController::class, 'PostsWhoesMembersCommentFrom20To30']);
Route::apiResources([
    'users' => Admin\API\UserController::class,
    'posts' => Admin\API\PostController::class,
]);

Route::get('/getGuestList', 'ChatController@getGuestList' );
Route::delete('/deleteChat', 'ChatController@deleteChat' );
Route::patch('/markAsRead/{guest}', 'ChatController@markAsRead' );
Route::patch('/chatEnd/{guest}', 'ChatController@chatEnd' );
Route::delete('/deleteChatAll', 'ChatController@deleteChatAll' );
