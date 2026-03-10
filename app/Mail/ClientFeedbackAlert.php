<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Application;

class ClientFeedbackAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $action; // لمعرفة هل هو (قبول أم رفض)

    public function __construct(Application $application, $action)
    {
        $this->application = $application;
        $this->action = $action;
    }

    public function build()
    {
        $subject = 'تحديث من العميل: ' . $this->application->project->client->company_name . ' (' . $this->action . ')';
        
        return $this->subject($subject)
                    ->view('emails.client_feedback_alert');
    }
}