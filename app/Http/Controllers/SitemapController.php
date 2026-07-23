<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Course;

class SitemapController extends Controller
{
    public function index()
    {
        $blogs = Blog::select('slug', 'updated_at')->get();
        $courses = Course::where('is_active', 1)->select('slug', 'updated_at')->get();

        return response()
            ->view('sitemap', compact('blogs', 'courses'))
            ->header('Content-Type', 'text/xml');
    }
}
