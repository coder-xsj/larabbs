<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Auth;

/**
 *
 */
class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


	public function store(ReplyRequest $request, Reply $reply)
	{
        $reply->content = $request->content;
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
//        dd('发布成功');
        $reply->save();

        return redirect()->to($reply->topic->link())->with('success', '评论发布成功！');

    }

	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

		return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
	}
}
