<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Auth;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable implements MustVerifyEmailContract
{
    use Notifiable, MustVerifyEmailTrait, HasRoles;

//    use Notifiable {
//        notify as protected laravelNotify;
//    }
//    public function notify($instance){
//
//        // 判断是否是数据库类型通知，如果不是则不通知
//        if(method_exists($instance, 'toDatabase')){
//            // 通知的是自己，就不用了
//
//        }
//
//        $this->laravelNotify($instance);
//    }

    public function topicNotify($instance){
        // 通知的是自己，就不用了
        if($this->id == Auth::id()){
            return;
        }
        $this->increment('notification_count');
        $this->notify($instance);
    }

    // 将消息置0
    public function markAsRead(){
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 一个用户可以发布多个话题
    public function topics(){
        return $this->hasMany(Topic::class);
    }

    // 是否为当前账号
    public function isAuthorOf($model){
        return $this->id == $model->user_id;
    }

    // 一个用户可以发布多条评论
    public function replies(){
        return $this->hasMany(Reply::class);
    }

    // 设置密码
    public function setPasswordAttribute($value){
        // 如果值的长度为60，被认为已经做过加密处理
        if(strlen($value) != 60){
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;

    }

    // 设置头像
    public function setAvatarAttribute($path){
        // 如果路径不是以 http 字符串开头，就被认为是后台修改的
        if(! \Str::startsWith($path, 'http')){
            // 补全url
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }
        $this->attributes['avatar'] = $path;
    }
}
