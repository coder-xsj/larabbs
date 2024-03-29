<?php

namespace App\Notifications;

use App\Models\Reply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\JPushChannel;
use JPush\PushPayload;

class TopicReplied extends Notification
{
    use Queueable;
    public $reply;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        //
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database', 'mail'];
//        return ['database', 'mail', JPushChannel::class];
    }

    // 数据库类型通知
    public function toDatabase($notifiable){
        $topic = $this->reply->topic;
        $link = $topic->link(['#reply' . $this->reply->id]);

        // 存放进数据库 data 字段
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,

        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // 邮箱类型通知
    public function toMail($notifiable){
        $topic = $this->reply->topic;
        $url = $topic->link(['#reply' . $this->reply->id]);

        //$url = $this->reply->topic->link(['#reply'] . $this->reply->id);

        return (new MailMessage)
                ->line('你的话题有新回复')
                ->action('查看回复', $url);

    }

//    public function toJPush($notifiable, PushPayload $payload): PushPayload{
//        return $payload
//            ->setPlatform('all')
//            ->addRegistrationId($notifiable->registeration_id)
//            ->setNotificationAlert(strip_tags($this->reply->content));
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
