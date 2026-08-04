<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->paginate(12);

        return view('courses', compact('courses'));
    }

    public function detail($slug)
    {
        $course = Course::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();
        $testimonials = Testimonial::where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->take(3)
            ->get();

        if ($testimonials->isEmpty()) {
            $testimonials = Testimonial::where('is_active', 1)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'DESC')
                ->take(3)
                ->get();
        }

        return view('course_detail', compact('course', 'testimonials'));
    }
}
