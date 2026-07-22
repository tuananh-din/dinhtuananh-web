<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blogs(){
        $blogs = Blog::orderBy('id','DESC')->paginate(12);
        return view('blogs',compact('blogs'));
    }

    public function detail($slug){
        $blog = Blog::where('slug',$slug)->firstOrFail();
        return view('blog_detail',compact('blog'));
    }
}
