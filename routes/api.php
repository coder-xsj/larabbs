<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// api版本测试
Route::prefix('v1')
    ->namespace('api')
    ->name('api.v1.')
    ->group(function (){
        // 用户注册
        Route::post('users', 'UsersController@store')->name('users.store');
        // 第三方登录
        Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->where('social_type', 'wechat|weibo') // 支持微信和微博
            ->name('socials.authorizations.store');
        // 登录
        Route::post('authorizations', 'AuthorizationsController@store')
            ->name('authorizations.store');
        // 删除和刷新 token 的路由
        Route::put('authorizations/current', 'AuthorizationsController@update')
            ->name('authorizations.update');
        Route::delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('authorizations.destroy');
});



