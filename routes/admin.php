<?php
// why not work?
Route::namespace('Admin')->group(function () {
   Route::get('/{slug}','AdminController@index');
});