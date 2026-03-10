<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class CandidateCvToClient extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Candidate Submission for: ' . $this->application->project->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.candidate_cv', // سنقوم بإنشاء هذا الملف في الخطوة القادمة
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        // التأكد من أن مسار الملف مسجل في قاعدة البيانات
        if ($this->application->candidate->redacted_cv_path) {
            
            // تحديد المسار الكامل للملف على السيرفر
            $fullPath = storage_path('app/public/' . $this->application->candidate->redacted_cv_path);

            // فحص أمان: هل الملف موجود فعلياً على القرص الصلب؟
            if (file_exists($fullPath)) {
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as('Candidate_CV_' . $this->application->candidate->profession . '.pdf')
                    ->withMime('application/pdf');
            }
        }

        return $attachments;
    }
}