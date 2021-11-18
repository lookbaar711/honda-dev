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

//http://localhost/josh_work/public/api/news
Route::get('1000', 'API\NewsController@index');

//http://localhost/josh_work/public/api/news?api_token=ZYHzVdSU38emoPTAH2PEcB5jYZJsBKkrrPHUplK109RiPUBNO9nE9KnRpPSm
Route::get('news', 'API\NewsController@index')->middleware('auth:api');
