<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadNotification;
use App\Models\Course;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $successMessage = html_entity_decode('&#272;&#259;ng k&#253; th&#224;nh c&#244;ng. Ch&#250;ng t&#244;i s&#7869; li&#234;n h&#7879; s&#7899;m.', ENT_QUOTES, 'UTF-8');
        // Honeypot: nếu bot điền field "website" (ẩn khỏi user thật) thì bỏ qua nhưng trả success giả để không lộ.
        if ($request->filled('website')) {
            return redirect()->back()->with('success', $successMessage);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
            'source_page' => 'nullable|string|max:255',
            'course_id' => 'nullable|integer',
        ]);

        $sourcePage = $request->source_page ?: 'unknown';
        $courseId = null;

        if ($request->course_id) {
            $course = Course::find($request->course_id);
            $courseId = $course ? $course->id : null;
        }

        $lead = Lead::create([
            'course_id' => $courseId,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'message' => $request->message,
            'source_page' => $sourcePage,
            'status' => 'new',
        ]);

        try {
            Mail::to(config('mail.lead_notify'))
                ->send(new NewLeadNotification($lead));
        } catch (\Throwable $e) {
            Log::error('Lead notification email failed.', [
                'lead_id' => $lead->id,
                'exception_class' => $e::class,
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }
}
