<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
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

    public function show(User $user, Request $request){
        return new UserResource($user);
    }
    public function me(Request $request){
        return (new UserResource($request->user()))->showSensitiveFields();
    }
}
