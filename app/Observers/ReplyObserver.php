<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        // 命令允许迁移时不做这些操作
        if(!app()->runningInConsole()){
            $topic = $reply->topic;
            // 未读消息 + 1
            $topic->increment('reply_count', 1);
            $topic->updateReplyCount();
            // 通知话题作者有新的评论
            $topic->user->topicNotify(new TopicReplied($reply));
        }

    }

    public function creating(Reply $reply){
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function deleted(Reply $reply){
        //
        $reply->topic->updateReplyCount();
    }


}
