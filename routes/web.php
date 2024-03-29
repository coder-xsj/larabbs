<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'TopicsController@index')->name('root');

// Auth::routes();
// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);

Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);

Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

// 上传图片路由
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

// seo
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

// 评论
Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

// 通知类
Route::resource('notifications', 'NotificationsController', ['only' => 'index']);

// 后台拒绝路由
Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');

// 关注列表
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');

// 粉丝列表
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');

// 关注用户
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');

// 取关用户
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');
