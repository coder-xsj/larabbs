<?php

return [
    // HTTP 请求的超时时间 单位 s
    'timeout' => 10.0,
    // 默认发送配置
    'default' => [
        // 网关调用策略，默认顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
        // 默认可以发送的网关
        'gateways' => [
            'aliyun',
            'yunpian'
        ],
    ],
    // 可用的网关配置
    'gateways' => [
        'errorlog' => [
            'file' => '/tmp/easy-sms.log',
        ],
        'aliyun' => [
            'access_key_id' => env('SMS_ALIYUN_ACCESS_KEY_ID'),
            'access_key_secret' => env('SMS_ALIYUN_ACCESS_KEY_SECRET'),
            'sign_name' => '记录我的快乐生活',
            'templates' => [
                'register' => env('SMS_ALIYUN_TEMPLATE_REGISTER')
            ],
        ],
        'yunpian' => [
            'api_key' => env('SMS_YUMPIAN_API_KEY_ID'),
            'sign_name' => env('SMS_YUNPIAN_SIGN_NAME'),
        ],
    ],
];
