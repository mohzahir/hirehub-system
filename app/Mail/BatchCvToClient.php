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
    public $summaryFilePath; // 1. إضافة متغير ملف الإكسيل

    // 2. تحديث البنّاء لاستقبال مسار الملف الجديد
    public function __construct(Collection $applications, Project $project, $summaryFilePath = null)
    {
        $this->applications = $applications;
        $this->project = $project;
        $this->summaryFilePath = $summaryFilePath;
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
            view: 'emails.batch_cvs', 
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        // 3. إرفاق ملف الإكسيل (الملخص) أولاً ليكون في واجهة الإيميل
        if ($this->summaryFilePath && file_exists($this->summaryFilePath)) {
            $attachments[] = Attachment::fromPath($this->summaryFilePath)
                ->as('Candidates_Summary.csv')
                ->withMime('text/csv');
        }

        // 4. الدوران على كل المرشحين المحددين وإرفاق ملفاتهم المظللة (كودك الأصلي الجميل)
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