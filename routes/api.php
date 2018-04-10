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
* 腾讯云ASR回调地址
*/

Route::group(['namespace' => 'Record'], function(){

    // 腾讯云ASR回调地址
    Route::any('ten/asr/call_back', 'RecordController@asrCallBack');

});

/**
 * 下载
 */
Route::group(['namespace' => 'Record', 'prefix' => 'record'], function(){
    //下载
    Route::any('down_load', 'DownLoadController@downLoadRecord');
});


Route::group(['namespace' => 'Record', 'prefix' => 'record'], function(){

    //呼出
    Route::any('call_out', 'RecordController@setCallOut');

});