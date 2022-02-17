<?php

namespace App\Http\Controllers;
use TCG\Voyager\Models\Page;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function show($id) {
      
        $page = Page::where('id', $id)->first();

        return view('pages.single',compact('page'));

    }
}

