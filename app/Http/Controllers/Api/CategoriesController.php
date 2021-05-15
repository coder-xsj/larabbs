<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(){
        // 让无分页的集合也放在 data 下面
        CategoryResource::wrap('data');
        // 返回所有分类的信息
        return CategoryResource::collection(Category::all());
    }
}
