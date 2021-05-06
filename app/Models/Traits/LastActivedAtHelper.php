<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt(){
        // 获取今天的日期
       // Redis 哈希表的命名，larabbs_last_actived_at_2021-5-6
       $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
       // 字段名称 user_1
       $field = $this->getHashField();
       // 获取哈希表里的全部数据
       //dd(Redis::hGetAll($hash));
       // 当前时间 2021-5-6 16:22:22
       $now = Carbon::now()->toDateTimeString();
       // 数据写入Redis，字段已存在会被更新
       Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt(){
        // 获取昨天的日期
        $hash = $this->getHashFromDateString(Carbon::yesterday()->toDateString());
        // 从 Redis 中获取所有数据
        $dates = Redis::hGetAll($hash);
        // 遍历并同步到数据库中
        foreach ($dates as $user_id => $actived_at){
            // 将 user_1 转化为 1
            $user_id = str_replace($this->field_prefix, '', $user_id);
            // 只有当用户存在时才更新到数据库中
            if($user = $this->find($user_id)){
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }
        // 数据库已同步，缓存即可删除
        Redis::del($hash);

    }

    public function getLastActivedAtAttribute($value){
        // 获取今日对应的哈希表名称
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());
        $filed = $this->getHashField();
        $datetime = Redis::hGet($hash, $filed) ? : $value;
        // 如果存在的话，返回时间对用的 Carbon 实体
        if($datetime){
            return new Carbon($datetime);
        } else {
            // 否则使用用户注册时间
            return $this->created_at;
        }
    }

    public function getHashFromDateString($date){
        return  $this->hash_prefix . $date;
    }
    public function getHashField(){
        return  $this->field_prefix . $this->id;

    }
}
