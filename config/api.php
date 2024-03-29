<?php

    /*
     * 接口频率限制
     */
    return [
        'rate_limits' => [
            // 访问频率限制，次数/分钟
            'access' => env('RATE_LIMITS', '60,1'),
            // 登录相关，次数/分钟
            'sign' => env('SIGN_RATE_LIMITS', '20,1'),
        ],
    ];
