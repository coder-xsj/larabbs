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
}
