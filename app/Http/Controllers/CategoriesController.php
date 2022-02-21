<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\User;
use App\Models\Link;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function show(Category $category, User $user, Link $link){
        // 读取分类id关联的话题，20一页
        $topics = Topic::where('category_id', $category->id)->paginate(20);
        // 活跃用户列表
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached(); // 获取

        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
