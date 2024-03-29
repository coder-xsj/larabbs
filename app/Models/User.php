<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Auth;
use Illuminate\Notifications\Notification;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
class User extends Authenticatable implements MustVerifyEmailContract, JWTSubject
{
    use Notifiable, MustVerifyEmailTrait, HasRoles;
    use Traits\ActiveUserHelper;
    use Traits\LastActivedAtHelper;
    use Notifiable {
        notify as protected laravelNotify;
    }


    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }

    public function notify($instance){
        // 判断是否是数据库类型通知，如果不是则不通知
        if(method_exists($instance, 'toDatabase')){
            // 如果要通知的人是当前用户，就不必通知了！
//            if ($this->id == Auth::id()) {
//                return;
//            }
            $this->increment('notification_count');

        }
        $this->laravelNotify($instance);
    }


    // 将消息置0
    public function markAsRead(){
        $this->notification_count = 0;
        $this->unreadNotifications->markAsRead();
        $this->save();


    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction', 'avatar',
        'weixin_openid', 'weixin_unionid', 'phone', 'registration_id',
        'weixin_session_key', 'weapp_openid',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
        'weixin_openid', 'weixin_unionid',
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

    // 一个用户有多个粉丝 - 获取粉丝关系列表
    public function followers() {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 获取用户关注人列表
    public function followings() {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 关注
    public function follow($user_ids) {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    // 取关
    public function unfollow($user_ids) {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    // 判断当前登录的用户 A 是否关注了用户 B
    public function isFollowing($user_id) {
        return $this->followings->contains($user_id);
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
