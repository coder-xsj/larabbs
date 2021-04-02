<?php

namespace App\Models;

use App\Category;
class Topic extends Model
{

    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    // 一个话题属于一个分类
    public function category(){
        return $this->belongsTo(Category::class);
    }
    // 一个话题属于一个作者
    public function user(){
        return $this->belongsTo(User::class);
    }
    // 一个帖子下有多条评论
    public function replies(){
        return $this->hasMany(Reply::class);
    }
    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
    }

    public function scopeRecentReplied($query)
    {
        // 当话题有新回复时，我们将编写逻辑来更新话题模型的 reply_count 属性，
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    public function link($params = []){
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    // 冗余代码复用
    public function updateReplyCount(Reply $reply){
        $this->reply_count = $this->replies->count();
        $this->save();
    }
}
