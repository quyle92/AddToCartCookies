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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResources([
    'users' => Admin\API\UserController::class,
    'posts' => Admin\API\PostController::class,
]);

Route::get('/getGuestList', 'ChatController@getGuestList' ); 
Route::delete('/deleteChat', 'ChatController@deleteChat' ); 
Route::patch('/markAsRead/{guest}', 'ChatController@markAsRead' ); 
Route::patch('/chatEnd/{guest}', 'ChatController@chatEnd' ); 
Route::delete('/deleteChatAll', 'ChatController@deleteChatAll' ); 

