<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Page extends Model
{
    public $table = "pages";
    public $fillable = ['author_id','title','excerpt','body','image','slug','meta_description','meta_keywords','status','created_at','updated_at'];
}

