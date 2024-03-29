<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\WeappAuthorizationRequest;
Use App\Models\User;
Use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Overtrue\Socialite\AccessToken;
use Illuminate\Auth\AuthenticationException;
use App\Http\Requests\Api\SocialAuthorizationRequest;
Use App\Http\Requests\Api\AuthorizationRequest;
//use App\Http\Controllers\Controller;

class AuthorizationsController extends Controller
{
    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    // 登录
    // authorizations.store
    public function store(AuthorizationRequest $request){
        $username = $request->username;
        // 验证是否是邮箱
        // 用户可以使用邮箱或者手机号登录
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;
        $credentials['password'] = $request->password;
        if(!$token = \Auth::guard('api')->attempt($credentials)){
            throw new AuthenticationException(trans('auth.failed'));
        }

        // 返回 json 数据
        return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function socialStore($type, SocialAuthorizationRequest $request){
        $driver = \Socialite::driver($type);
        try {
            if($code = $request->code){
                $accessToken = $driver->getAccessToken($code);
            }else{
                $tokenData['access_token'] = $request->access_token;

                // 微信需要增加 openid
                if($type == 'wechat'){
                    $tokenData['openid'] = $request->openid;
                }
                $accessToken = new AccessToken($tokenData);
            }

            $oauthUser = $driver->user($accessToken);
        } catch (\Exception $e){
            throw new AuthenticationException('参数错误，未获取用户信息');
        }

        //
        switch ($type){
            case 'wechat':
                $unionid = $oauthUser->getOriginal()['unionid'] ?? null;

                if($unionid){
                    $user = User::where('weixin_unionid', $unionid)->first();
                }else{
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                // 第一次微信授权登录
                if(!$user){
                    $user = User::create([
                        'name' => $oauthUser->getNickname(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }

                break;

        }
        $ttl = 365 * 24 * 60;
        $token = auth('api')->setTTL($ttl)->login($user);
        // login 方法为某一个用户模型生成 token
//        $token = auth('api')->login($user);
//        $token = \Auth::guard('api')->login($user);

        return $this->respondWithToken($token)->setStatusCode(201);
    }

    // 刷新token
    public function update(){
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    // 删除token
    public function destroy(){
        auth('api')->logout();
        return response(null, 204);
    }

    public function weappStore(WeappAuthorizationRequest $request) {
        $code = $request->code;

//        dd($code);
        // 根据 code 获取 access_token、openid
        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($code);

        // 如果有错误，说明 code 已过期或者不正确，返回 401 错误
        if (isset($data['errcode'])){
            throw new AuthenticationException('code 不正确');
        }

        // 找到 openid 对应用户
        $user = User::where('weapp_openid', $data['openid'])->first();

//        dd($user);
        $attributes['weixin_session_key'] = $data['session_key'];

        // 未找到对应用户则需要提交用户名密码进行用户绑定
        if (!$user) {
            // 如果未提交用户名密码，403 错误提示
            if (!$request->username) {
                throw new AuthenticationException('用户不存在');
            }

            $username = $request->username;

            // 用户名可以是邮箱或电话
            filter_var($username, FILTER_VALIDATE_EMAIL) ?
                $credentials['email'] = $username :
                $credentials['phone'] = $username;

            $credentials['password'] = $request->password;

            // 验证用户名和密码是否正确 --- 这一个方法不是很理解
            if (!auth('api')->once($credentials)) {
                throw new AuthenticationException('用户名和密码错误');
            }

            // 获取对应客户
            $user = auth('api')->getUser();
            $attributes['weapp_openid'] = $data['openid'];
        }

        // 更新用户数据
        $user->update($attributes);

        // 为用户创建 JWT
        $token = auth('api')->login($user);

        return $this->respondWithToken($token)->setStatusCode(201);
    }
}


