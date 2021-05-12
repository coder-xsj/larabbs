<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['type', 'path'];

    // 一个头像属于一个用户
    public function user(){
        return $this->belongsTo(User::class);
    }
}
