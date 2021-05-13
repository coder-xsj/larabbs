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
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function (){
        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function (){
                // 短信验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')
                    ->name('verificationCodes.store');
                // 用户注册
                Route::post('users', 'UsersController@store')->name('users.store');
                // 图片验证码
                Route::post('captchas', 'CaptchasController@store')
                    ->name('captchas.store');
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


        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function (){
                // 游客可以访问的接口
                // 某个用户的详情
                Route::get('users/{user}', 'UsersController@show')
                    ->name('users.show');

                // 分类列表
                Route::get('categories', 'CategoriesController@index')
                    ->name('categories.index');

                // 话题列表
                Route::resource('topics', 'TopicsController')->only(['index', 'show']);

                // 某个用户发布的话题
                Route::get('users/{user}/topics', 'TopicsController@userIndex')
                    ->name('users.topics.index');

                Route::middleware('auth:api')->group(function (){
                    // 登陆后可以访问的接口
                    // 当前登录用户信息
                    Route::get('user', 'UsersController@me')
                        ->name('user.show');

                    // 编辑登录用户信息
                    Route::patch('user', 'UsersController@update')
                        ->name('user.update');

                    // 上传图片
                    Route::post('images', 'ImagesController@store')
                        ->name('images.store');

                    // 发布话题
                    Route::resource('topics', 'TopicsController')
                        ->only(['store', 'update', 'destroy']);

                    // 发布评论
                    Route::post('topics/{topic}/replies', 'RepliesController@store')
                        ->name('topics.replies.store');

                    // 删除评论
                    Route::delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                        ->name('topics.replies.destroy');
                });
            });
});



