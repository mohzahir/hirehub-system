<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;

class ClientInterviewSchedule extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $candidates;
    public $interview_date;
    public $interview_time;
    public $interview_link;

    public function __construct(Project $project, $candidates, $interview_date, $interview_time, $interview_link)
    {
        $this->project = $project;
        $this->candidates = $candidates;
        $this->interview_date = $interview_date;
        $this->interview_time = $interview_time;
        $this->interview_link = $interview_link;
    }

    public function build()
    {
        return $this->subject('جدول مقابلات مرشحين - مشروع: ' . $this->project->title)
                    ->view('emails.client_interview_schedule');
    }
}