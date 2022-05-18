<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
       return [
           // 评论限制最少两个字符
           'content' => 'required|min:2',
       ];
    }

    public function messages()
    {
        return [
            // Validation messages
            'content.required' => '内容不能为空',
            'content.min' => '内容必须至少两个字符',
        ];
    }
}
