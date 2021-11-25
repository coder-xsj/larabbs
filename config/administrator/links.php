<?php
use App\Models\Link;

return [
    'title' => '资源推荐',
    'single' => '资源推荐',

    'model' => Link::class,

    // 只允许站长管理
    'permission' => function(){
        return Auth::user()->hasRole('Founder');
    },

    'columns' => [
        'id' => [
            'title' => 'ID'
        ],
        'title' => [
            'title' => '书名',
            'sortable' => false,
//            'output' => function($link){
//                return  '<a href=' . '"$link->link"' . '></a>';
//            },
        ],
        'image' => [
            'title' => '封面',
            'sortable' => false,
            // 默认情况下会直接输出数据，你也可以使用 output 选项来定制输出内容
            'output' => function ($image, $model) {
                return empty($image) ? 'N/A' : '<img src="'.$image.'" width="40">';
            }
        ],
        'link' => [
            'title' => '链接',

            'sortable' => false,
        ],
        'created_at' => [
            'title' => '创建时间',
        ],
        'operation' => [
            'title' => '管理',
            'sortable' => false,
        ],
    ],

    'edit_fields' => [
        'title' => [
            'title' => '名称',
        ],
        'link' => [
            'title' => '链接',
        ],
        'image' => [
            'title' => '封面',

            'type' => 'image',

            // 图片上传必须设置图片存放路径
            'location' => public_path() . '/uploads/images/links/',

        ],
    ],

    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'title' => [
            'title' => '书名',
        ],


    ],


];
