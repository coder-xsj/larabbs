<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Topic;
use App\Models\Reply;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;
use function Symfony\Component\Translation\t;

class UsersController extends Controller
{
    // modekeys
    protected $primaryKey = 'id';

    public function __construct(){
        $this->middleware('auth', [
            'except' => ['show'],
        ]);
    }

    public function show(User $user){
        // 发帖数
        $topicNum = $user->topics->count();
        // 评论数
        $replyNum = $user->replies()->count();
        // 粉丝数
        return view('users.show', compact('user', 'topicNum', 'replyNum'));
    }

    public function edit(User $user){
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user){
        $this->authorize('update', $user);
        $data = $request->all();
        if($request->avatar){
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 416);
            if($result){
                $data['avatar'] = $result['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功');

    }

    // 关注列表
    public function followings(User $user) {
        $users = $user->followings()->paginate(10);
        $title = $user->name . ' 关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    // 粉丝列表
    public function followers(User $user) {
        $users = $user->followers()->paginate(10);
        $title = $user->name . ' 的粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
