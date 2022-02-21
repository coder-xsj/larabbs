<?php

namespace App\Observers;

use App\Jobs\TranslateSlug;
use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }
    // 存储话题的摘录
    public function saving(Topic $topic){
        // 转义标签 Xss 过滤
        $topic->body = clean($topic->body, 'user_topic_body');
        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

    }

    public function saved(Topic $topic){
        // 进入队列任务
        if ((! $topic->slug))  {
            dispatch(new TranslateSlug($topic));
        }
    }
    // 删除当前帖子下的评论
    public function deleted(Topic $topic){
        \DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}
