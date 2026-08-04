<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Image;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\LeadMagnet;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $about = About::first() ?? new About();
        $jobs = Service::orderBy('id','DESC')->get();
        $words = Service::pluck('title');
        $skills = Skill::orderBy('number','DESC')->take(4)->get();
        $blogs = Blog::orderBy('id','DESC')->take(3)->get();
        $cases = Image::where('type',0)->orderBy('id','DESC')->take(3)->get();
        $featuredCourse = Course::where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$featuredCourse) {
            $featuredCourse = Course::where('is_active', 1)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'DESC')
                ->first();
        }

        $highlightCourses = Course::where('is_active', 1)
            ->when($featuredCourse, function ($query) use ($featuredCourse) {
                $query->where('id', '!=', $featuredCourse->id);
            })
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->take(2)
            ->get();

        $featuredTestimonials = Testimonial::where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->take(6)
            ->get();

        if ($featuredTestimonials->isEmpty()) {
            $featuredTestimonials = Testimonial::where('is_active', 1)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('id', 'DESC')
                ->take(6)
                ->get();
        }

        $leadMagnet = LeadMagnet::where('is_active', 1)->latest()->first();
        return view('home',compact('about','jobs','words','skills','blogs','cases','featuredCourse','highlightCourses','featuredTestimonials','leadMagnet'));
    }
    public function about(){
        $about = About::first() ?? new About();
        $image = Image::where('type',1)->orderBy('id','DESC')->first();
        $skills = Skill::orderBy('number','DESC')->take(6)->get();
        $jobs = Service::orderBy('id','DESC')->take(4)->get();

        return view('about',compact('about','image','skills','jobs'));
    }
    public function contact(Request $request)
    {
        $courses = Course::where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();
        $selectedCourse = $courses->firstWhere('slug', $request->query('course'));

        return view('contact', compact('courses', 'selectedCourse'));
    }
    public function life(){
        $images = Image::where('type',0)->orderBy('id','DESC')->get();

        return view('life',compact('images'));
    }
    public function portfolio(){
        $about = About::first() ?? new About();
        $jobs = Service::orderBy('id','DESC')->get();
        $skills = Skill::orderBy('id','DESC')->get();
        $image = Image::where('type',1)->orderBy('id','DESC')->first();
        $lifes = Image::where('type',0)->orderBy('id','DESC')->get();
        return view('portfolio',compact('about','jobs','skills','image','lifes'));
    }
}
