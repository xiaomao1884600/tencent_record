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

/**
* 腾讯云oauth2回调地址
*/
Route::any('tencentyun/asr/call_back', function(Request $request){
    $content = json_encode($request->all()) . "\n";
    file_put_contents(public_path('tencentyun_auth_call_back.json'), $content, FILE_APPEND);
    return ['success' => true];
});

Route::group(['namespace' => 'Record', 'prefix' => 'record'], function(){

    //呼出
    Route::any('call_out', 'RecordController@setCallOut');

});