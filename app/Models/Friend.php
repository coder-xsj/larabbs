<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
class Friend extends Model
{
    //
    protected $fillable = [
        'name',
        'link',
        'avatar',
    ];
    // 缓存相关配置
    public $cache_key = 'larabbs_friends';
    protected $cache_expire_in_seconds = 1440 * 60; // 24 小时

    public function getAllCached() {
        // 尝试从缓存中取出 cache_key 对应的数据，如果能取到，则直接返回数据
        // 否则运行匿名函数中的 all 方法来取出 links 表中的数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function () {
            return $this->all();
        });
    }

    // 设置友链头像
    public function setAvatarAttribute($path) {
        // 如果路径不是以 http 字符串开头，就被认为是后台修改的
        if (!\Str::startsWith($path, 'http')) {
            // 补全url
            $path = config('app.url') . "/uploads/images/friends/$path";
        }
        $this->attributes['avatar'] = $path;
    }
}
