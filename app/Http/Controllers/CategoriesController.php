<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;
use App\Models\Link;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category,Request $request, User $user, Link $link, Friend $friend){
        // 读取分类 id 关联的话题，20一页
        $topics = Topic::where('category_id', $category->id)->paginate(20);
        // 活跃用户列表
        $active_users = $user->getActiveUsers();
        // 资源链接
        $links = $link->getAllCached();
        // 友情链接
        $friends = $friend->getAllCached();
        return view('topics.index', compact('topics', 'category', 'active_users', 'links', 'friends'));
    }
}
