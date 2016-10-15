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

Route::group(['middleware' => 'cors'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:api');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/contacts', 'ContactController@index');
        Route::post('/contacts', 'ContactController@store');
        Route::get('/contacts/{id}', 'ContactController@show');
        Route::put('/contacts/{id}', 'ContactController@update');
        Route::patch('/contacts/{id}', 'ContactController@update');
        Route::delete('/contacts/{id}', 'ContactController@destroy');
    });

});
