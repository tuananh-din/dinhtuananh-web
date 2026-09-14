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
        $sortInput = $request->query('sort');
        $search = is_string($searchInput) ? mb_substr(trim($searchInput), 0, 120) : '';
        $categorySlug = is_string($categoryInput) ? trim($categoryInput) : '';
        $sort = is_string($sortInput) && in_array($sortInput, ['relevance', 'latest', 'oldest'], true)
            ? $sortInput
            : ($search !== '' ? 'relevance' : 'latest');

        if ($search === '' && $sort === 'relevance') {
            $sort = 'latest';
        }
        $categories = Category::query()
            ->whereHas('blogs', fn ($query) => $query->where('is_published', 1))
            ->orderBy('name')
            ->get();
        $selectedCategory = $categories->firstWhere('slug', $categorySlug);
        $blogs = Blog::where('is_published', 1)
            ->with('categories')
            ->when($search !== '', fn ($query) => $query->where(fn ($searchQuery) => $searchQuery
                ->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")))
            ->when($categorySlug !== '', fn ($query) => $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->where('slug', $categorySlug)))
            ->when($sort === 'relevance' && $search !== '', fn ($query) => $query
                ->orderByRaw('CASE WHEN title LIKE ? THEN 0 ELSE 1 END', ["%{$search}%"])
                ->latest())
            ->when($sort === 'oldest', fn ($query) => $query->oldest())
            ->when($sort === 'latest' || ($sort === 'relevance' && $search === ''), fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        return view('blogs', compact(
            'blogs', 'categories', 'search', 'categorySlug', 'selectedCategory', 'sort'
        ));
    }

    public function detail($slug){
        $blog = Blog::where('is_published', 1)->where('slug', $slug)->first();

        if (!$blog) {
            $blog = Blog::where('is_published', 1)
                ->where('slug', 'like', '%dinhtuananhcom%')
                ->get()
                ->first(fn (Blog $candidate) => $candidate->public_slug === $slug);
        }

        abort_unless($blog, 404);

        if ($slug !== $blog->public_slug) {
            return redirect()->route('blog', $blog->public_slug, 301);
        }

        $otherBlogs = Blog::where('is_published', 1)->where('id','!=',$blog->id)->orderBy('id','DESC')->limit(3)->get();
        $featuredCourse = Course::where('is_active', 1)->where('is_featured', 1)->orderBy('sort_order')->orderByDesc('id')->first();
        if (!$featuredCourse) {
            $featuredCourse = Course::where('is_active', 1)->orderBy('sort_order')->orderByDesc('id')->first();
        }
        return view('blog_detail', compact('blog', 'otherBlogs', 'featuredCourse'));
    }
}
