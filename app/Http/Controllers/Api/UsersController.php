<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Image;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Auth\AuthenticationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function store(UserRequest $request){
        $verifyData = \Cache::get($request->verification_key);
        if(!$verifyData){
            abort(403, '验证码已失效');
        }
        if(!hash_equals($verifyData['code'], $request->verification_code)){
            throw new AuthenticationException('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'password' => $request->password,
            'phone' => $verifyData['phone'],
        ]);
        // 清除缓存验证码
        \Cache::forget($request->verification_key);
        return (new UserResource($user))->showSensitiveFields();
    }

    // 小程序登录
    public function weappStore(UserRequest $request)
    {
        // 缓存中是否存在对应的 key
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            abort(403, '验证码已失效');
        }

        // 判断验证码是否相等，不相等反回 401 错误
        if (!hash_equals((string)$verifyData['code'], $request->verification_code)) {
            throw new AuthenticationException('验证码错误');
        }

        // 获取微信的 openid 和 session_key
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($request->code);

        if (isset($data['errcode'])) {
            throw new AuthenticationException('code 不正确');
        }

        // 如果 openid 对应的用户已存在，报错403
        $user = User::where('weapp_openid', $data['openid'])->first();

        if ($user) {
            throw new AuthenticationException('微信已绑定其他用户，请直接登录');
        }

        // 创建用户
        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => $request->password,
            'weapp_openid' => $data['openid'],
            'weixin_session_key' => $data['session_key'],
        ]);

        return (new UserResource($user))->showSensitiveFields();
    }


    public function show(User $user, Request $request){
        return new UserResource($user);
    }
    public function me(Request $request){
        return (new UserResource($request->user()))->showSensitiveFields();
    }

    public function update(UserRequest $request){
        $user = \Auth::guard('api')->user();
//        dd($userId);
//        die;

//        $user = $request->user();
//        dd($user);
        $attributes = $request->only(['name', 'email', 'introduction', 'registration_id']);

        if($request->avatar_image_id){
            $image = Image::find($request->avatar_image_id);
            $attributes['avatar'] = $image->path;
        }
        $user->update($attributes);

        return (new UserResource($user))->showSensitiveFields();
    }

    // 活跃用户列表
    public function activedIndex(User $user){
        UserResource::wrap('data');
        return UserResource::collection($user->getActiveUsers());
    }
}
