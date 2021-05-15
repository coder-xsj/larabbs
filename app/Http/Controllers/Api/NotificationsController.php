<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

class NotificationsController extends Controller
{
    public function index(Request $request){
        $notifications = $request->user()->notifications()->paginate();

        return NotificationResource::collection($notifications);
    }

    public function stats(Request $request){
        return response()->json([
            'unread_count' => $request->user()->notification_count,
        ]);
    }

    // 一键已读
    public function read(Request $request){
        $request->user()->markAsRead();

        return response(null, 204);
    }

    // 单个已读
    public function readSingle(Request $request, DatabaseNotification $notification){
        if(!$notification->read_at){
            $request->user()->decrement('notification_count');
            $notification->markAsRead();
        }

        return response(null, 204);
    }

}
