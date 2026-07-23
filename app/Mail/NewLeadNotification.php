<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLeadNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $courseTitle;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
        $this->courseTitle = null;

        if ($lead->course_id) {
            $course = Course::find($lead->course_id);
            $this->courseTitle = $course ? $course->title : null;
        }
    }

    public function build()
    {
        return $this->subject('Lead mới từ website')
            ->view('emails.new-lead')
            ->with([
                'lead' => $this->lead,
                'courseTitle' => $this->courseTitle,
            ]);
    }
}
