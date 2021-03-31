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
        ];
    }
}
