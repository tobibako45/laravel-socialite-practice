<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'BlogsController@index');

Route::get('/login/{provider}', 'Auth\LoginController@redirectToProvider');
// Route::get('/login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/login/passport/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/login/github/callback', 'Auth\LoginController@handleGithubProviderCallback');

Route::group(['middleware' => 'auth'], function() {
    Route::resource('/blogs', 'BlogsController');
});
