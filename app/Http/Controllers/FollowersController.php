<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class FollowersController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function store(User $user) {
        $this->authorize('follow', $user);
        // 如果当前登录用户没有关注此用户
        if (!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }
        return redirect()->route('users.followers', Auth::user('id'));
    }

    public function destroy(User $user) {
        $this->authorize('follow', $user);
        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
        }

        return redirect()->route('users.followers', Auth::user('id'));
    }

}
