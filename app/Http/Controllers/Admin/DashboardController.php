<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Course;
use App\Models\Lead;
use App\Models\Subscriber;
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
        $totalLeads = $leadCounts->sum();
        $leadsBySource = Lead::selectRaw("COALESCE(NULLIF(source_page, ''), 'unknown') as source, COUNT(*) as total")
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', [
            'blogCount' => Blog::count(),
            'activeCourseCount' => Course::where('is_active', 1)->count(),
            'testimonialCount' => Testimonial::count(),
            'subscriberCount' => Subscriber::count(),
            'leadCounts' => $leadCounts,
            'totalLeads' => $totalLeads,
            'leadsLast7Days' => Lead::where('created_at', '>=', now()->subDays(7))->count(),
            'leadsLast30Days' => Lead::where('created_at', '>=', now()->subDays(30))->count(),
            'leadsBySource' => $leadsBySource,
            'recentLeads' => $recentLeads,
        ]);
    }
}
