<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    //
    //use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'description'
    ];
}
