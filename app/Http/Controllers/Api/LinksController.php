<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Http\Resources\LinksResource;

class LinksController extends Controller
{
    public function index(Link $link){
        $links = $link->getAllCached();

        LinksResource::wrap('data');
        return LinksResource::collection($links);
    }
}
