<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class BatchCvToClient extends Mailable
{
    use Queueable, SerializesModels;

    public $applications;
    public $project;

    public function __construct(Collection $applications, Project $project)
    {
        $this->applications = $applications;
        $this->project = $project;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Candidates Batch for: ' . $this->project->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.batch_cvs', // سنقوم بإنشاء هذا الملف
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        // الدوران على كل المرشحين المحددين وإرفاق ملفاتهم المظللة
        foreach ($this->applications as $application) {
            if ($application->candidate->redacted_cv_path) {
                $fullPath = storage_path('app/public/' . $application->candidate->redacted_cv_path);
                
                if (file_exists($fullPath)) {
                    // تسمية الملف باسم المرشح ومهنته ليكون واضحاً للعميل
                    $fileName = $application->candidate->first_name . '_' . $application->candidate->profession . '_CV.pdf';
                    $attachments[] = Attachment::fromPath($fullPath)
                        ->as($fileName)
                        ->withMime('application/pdf');
                }
            }
        }

        return $attachments;
    }
}