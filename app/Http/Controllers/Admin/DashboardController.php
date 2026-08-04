<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Lead;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $leadCounts = Lead::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $recentLeads = Lead::with('course')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', [
            'blogCount' => Blog::count(),
            'activeCourseCount' => Course::where('is_active', 1)->count(),
            'testimonialCount' => Testimonial::count(),
            'leadCounts' => $leadCounts,
            'recentLeads' => $recentLeads,
        ]);
    }
}
