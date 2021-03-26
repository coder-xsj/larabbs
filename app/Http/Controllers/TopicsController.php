<?php

namespace App\Http\Controllers;

use App\Category;
use App\Handlers\ImageUploadHandler;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
//use Illuminate\Support\Facades\Auth;
use Auth;

class TopicsController extends Controller
{
    public function __construct()
    {
        // 限制未登录用户发帖
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic)
	{
        $topics = $topic->withOrder($request->order)
            ->with('user', 'category')  // 预加载防止 N+1 问题
            ->paginate(20);
        // $topics = Topic::with('user', 'category')->paginate(30);
		return view('topics.index', compact('topics'));
	}

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories = Category::all();
		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
	    $topic->fill($request->all());
	    $topic->user_id = Auth::id();
	    $topic->save();
		//$topic = Topic::create($request->all());
		return redirect()->route('topics.show', $topic->id)->with('success', '帖子创建成功！');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}

	// 上传图片逻辑
    public function uploadImage(Request $request, ImageUploadHandler $uploader){
        // 初始化返回的json数据
        $data = [
            'success' => false,
            'msg' => '上传失败！',
            'file_path' => ''
        ];
        // 判断是否有上传文件，并赋值给 $file
        if($file = $request->upload_file){
            // 图片保存到本地
            $result = $uploader->save($file, 'topics', \Auth::id(), 1024);
            // 图片保存成功的话
            if($result){
                $data['file_path'] = $result['path'];
                $data['msg'] = '上传成功！';
                $data['success'] = true;
            }
        }

        return $data;
    }
}
