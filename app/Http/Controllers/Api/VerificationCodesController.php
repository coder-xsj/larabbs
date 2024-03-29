<?php

namespace App\Http\Controllers\Api;

//use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms){

        $captchaData = \Cache::get($request->captcha_key);

        if(!$captchaData){
            abort(403, '图片验证码已失效');
        }

        if(!hash_equals($captchaData['code'], $request->captcha_code)){
            // 验证错误就清除缓存
            \Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }

        $phone = $captchaData['phone'];

        // 如果不是生产环境验证码统一为 1234
        if(!app()->environment('production')){
            $code = '1234';
        }else{
            // 生成 6位随机数，左侧补 0
            $code = str_pad(random_int(1, 9999), 6, 0, STR_PAD_LEFT);
            try {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            }catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception){
                $message = $exception->getException('aliyun')->getMessage();
                abort(500, $message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . Str::random(15);
        // 缓存验证码 5 分钟过期。
        $expiredAt = now()->addMinutes(5);
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        // 清除缓存验证码
        \Cache::forget($request->captcha_key);
        // 将 key 以及 过期时间 返回给客户端。
        return response()->json([
            'key' => $key,
            'expiredAt' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
