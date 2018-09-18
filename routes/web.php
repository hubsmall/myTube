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


Route::redirect('/', 'home', 301);

Route::resource('chanels.playlists', 'PlaylistController');
Route::resource('chanels.playlists.videos', 'VideoController');
Route::resource('chanels.videos', 'ChanelController');


Route::apiResources([
    'home' => 'HomeController',
]);