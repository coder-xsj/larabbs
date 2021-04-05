<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function root(){
        //dd(\Auth::user()->hasVerifiedEmail());
        return redirect()->route('topics.index');
//        return view('pages.root');
    }
    public function permissionDenied(){
        // 检测用户是否有权限登录后台
        if(config('administrator.permission')()){
            return redirect(url(config('administrator.uri')),302);
        }
        // 否则使用视图
        return view('pages.permission_denied');
    }
}
