<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blogs(Request $request){
        $searchInput = $request->query('search');
        $categoryInput = $request->query('category');
        $search = is_string($searchInput) ? trim($searchInput) : '';
        $categorySlug = is_string($categoryInput) ? trim($categoryInput) : '';
        $categories = Category::query()
            ->whereHas('blogs', fn ($query) => $query->where('is_published', 1))
            ->orderBy('name')
            ->get();
        $selectedCategory = $categories->firstWhere('slug', $categorySlug);
        $blogs = Blog::where('is_published', 1)
            ->with('categories')
            ->when($search !== '', fn ($query) => $query->where(fn ($searchQuery) => $searchQuery
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")))
            ->when($categorySlug !== '', fn ($query) => $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->where('slug', $categorySlug)))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('blogs', compact(
            'blogs', 'categories', 'search', 'categorySlug', 'selectedCategory'
        ));
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
