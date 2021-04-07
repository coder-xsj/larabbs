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
        'link' => [
            'title' => '链接',
//            'output' => function($link){
//            },
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
