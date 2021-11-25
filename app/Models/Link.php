<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
class Link extends Model {
    // 允许更新的字段
    protected $fillable = ['title', 'link', 'image'];

    // 缓存相关配置
    public $cache_key = 'larabbs_links';
    protected $cache_expire_in_seconds = 1440 * 60; // 24 小时

    public function getAllCached() {
        // 尝试从缓存中取出 cache_key 对应的数据，如果能取到，则直接返回数据
        // 否则运行匿名函数中的 all 方法来取出 links 表中的数据，返回的同时做了缓存。
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function () {
            return $this->all();
        });
    }

    // 设置头像
    public function setImageAttribute($path) {
        // 如果路径不是以 http 字符串开头，就被认为是后台修改的
        if (!\Str::startsWith($path, 'http')) {
            // 补全url
            $path = config('app.url') . "/uploads/images/links/$path";
        }
        $this->attributes['image'] = $path;
    }
}
