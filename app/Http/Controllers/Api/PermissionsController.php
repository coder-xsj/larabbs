<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PermissionsResource;

class PermissionsController extends Controller
{
    public function index(Request $request){
        $permissions = $request->user()->getAllPermissions();

        PermissionsResource::wrap('data');
        return PermissionsResource::collection($permissions);
    }
}
