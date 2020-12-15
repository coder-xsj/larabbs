<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function root(){
        //dd(\Auth::user()->hasVerifiedEmail());
        return view('pages.root');
    }
}
