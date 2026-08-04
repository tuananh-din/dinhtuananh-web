<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blogs(Request $request){
        $search = trim((string) $request->query('search'));
        $category = $request->query('category');
        $blogs = Blog::where('is_published', 1)->with('categories')->when($search, fn($q) => $q->where(fn($s) => $s->where('title', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%")))->when($category, fn($q) => $q->whereHas('categories', fn($c) => $c->where('slug', $category)))->latest()->paginate(12)->withQueryString();
        return view('blogs', ['blogs' => $blogs, 'categories' => Category::orderBy('name')->get(), 'search' => $search, 'selectedCategory' => $category]);
    }

    public function detail($slug){
        $blog = Blog::where('is_published', 1)->where('slug',$slug)->firstOrFail();
        $otherBlogs = Blog::where('is_published', 1)->where('id','!=',$blog->id)->orderBy('id','DESC')->limit(3)->get();
        $featuredCourse = Course::where('is_active', 1)->where('is_featured', 1)->orderBy('sort_order')->orderByDesc('id')->first();
        if (!$featuredCourse) {
            $featuredCourse = Course::where('is_active', 1)->orderBy('sort_order')->orderByDesc('id')->first();
        }
        return view('blog_detail', compact('blog', 'otherBlogs', 'featuredCourse'));
    }
}
