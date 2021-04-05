<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 放弃维护 created_at 和 updated_at 字段
    public $timestamps = false;
}
