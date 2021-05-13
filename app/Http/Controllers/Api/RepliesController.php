<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use App\Models\Topic;
use App\Http\Queries\ReplyQuery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function index($topicId, ReplyQuery $query){
//        $replies = $topic->replies()->paginate();
        $replies = $query->where('topic_id', $topicId)->paginate();

        return ReplyResource::collection($replies);
    }

    // 用户评论列表
    public function userIndex($userId, ReplyQuery $query){
        $replies = $query->where('user_id', $userId)->paginate();

        return ReplyResource::collection($replies);
    }

    public function store(ReplyRequest $request, Topic $topic, Reply $reply){
        $reply->content = $request->content;

        $reply->topic()->associate($topic);
        $reply->user()->associate($request->user());
        $reply->save();

        return new ReplyResource($reply);
    }

    public function destroy(Topic $topic, Reply $reply){
        if($reply->topic_id != $topic->id){
            abort(404);
        }
        $this->authorize('destroy', $reply);
        $reply->delete();

        return response(null, 204);
    }
}
