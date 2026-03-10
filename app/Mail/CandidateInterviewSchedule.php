<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;

class CandidateInterviewSchedule extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $candidate;
    public $interview_date;
    public $interview_time;
    public $interview_link;

    public function __construct(Project $project, $candidate, $interview_date, $interview_time, $interview_link)
    {
        $this->project = $project;
        $this->candidate = $candidate;
        $this->interview_date = $interview_date;
        $this->interview_time = $interview_time;
        $this->interview_link = $interview_link;
    }

    public function build()
    {
        return $this->subject('دعوة لمقابلة عمل - وكالة Hirehub')
                    ->view('emails.candidate_interview_schedule');
    }
}