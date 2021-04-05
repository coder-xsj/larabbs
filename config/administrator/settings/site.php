<?php

    return [
        'title' => '站点设置',
        // 只有站长才可以管理配置
        'permission' => function(){
            return Auth::user()->hasRole('Founder');
        },

        'edit_fields' => [
            'site_name' => [
                'title' =>'站点名称',
                'type' => 'text',
                'limit' => 50,
            ],
            'contact_email' => [
                'title' => '联系人邮箱',
                'type' => 'text',
                'limit' => 50
            ],
            'seo_description' => [
                'title' => 'SEO description',
                'type' =>  'textarea',
                'limit' => 250,
            ],
            'seo_keyword' => [
                'title' => 'SEO keyword',
                'type' => 'textarea',
                'limit' => 250,
            ],
        ],
        // 表单验证规则
        'rules' => [
            'site_name' => 'required|max:50',
            'contact_name' => 'email',
        ],
        'messages' => [
            'site_name.required' => '请填写站点名称',
            'site_name.max' => '字数已超出限制',
            'contact_name.email' => '请填写正确的邮箱格式',
        ],
        'before_save' => function(&$data){
            // 为网站添加后缀，防止多次添加
            if(strpos($data['site_name'], 'Powered By LaraBBS') === false){
                $data['site_name'] .= ' - Powered By LaraBBS';
            }
        },
        'actions' => [
            // 清空缓存
            'clear_cache' => [
                'title' => '更新系统缓存',
                'messages' => [
                    'active' => '正在清理缓存',
                    'success' => '清理缓存成功',
                    'error' => '清理缓存出错，请稍后操作',
                ],
                // 清理缓存
                'action' => function(&$data){
                    \Artisan::call('cache:clear');
                    return true;
                }
            ],
        ],
    ];
