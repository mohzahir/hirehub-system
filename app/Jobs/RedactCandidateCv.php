<?php

namespace App\Jobs;

use App\Models\Candidate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RedactCandidateCv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $candidate;

    // تمرير المرشح للمهمة
    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    // المنطق الذي سيعمل في الخلفية
    public function handle()
    {
        // التحقق من وجود السيرة الذاتية الأصلية
        if (!$this->candidate->original_cv_path) {
            return;
        }

        // المسار الكامل للملف الأصلي
        $originalFullPath = storage_path('app/public/' . $this->candidate->original_cv_path);
        
        // تجهيز اسم ومسار النسخة المظللة (الجديدة)
        $redactedFileName = 'redacted_' . basename($this->candidate->original_cv_path);
        $redactedRelativePath = 'cvs/redacted/' . $redactedFileName;
        $redactedFullPath = storage_path('app/public/' . $redactedRelativePath);

        // التأكد من وجود مجلد النسخ المظللة
        if (!Storage::disk('public')->exists('cvs/redacted')) {
            Storage::disk('public')->makeDirectory('cvs/redacted');
        }

        // مسار سكربت البايثون (بافتراض أنك وضعته في مجلد scripts داخل المشروع)
        $pythonScriptPath = base_path('scripts/cv_redactor.py');

        // التعديل السحري: استخدام البايثون الموجود داخل البيئة الافتراضية venv
        $pythonExecutable = base_path('venv/bin/python');

        // استدعاء البايثون الخاص بنا وتمرير مسار الملف الأصلي ومسار الملف الجديد كـ Arguments
        $process = new Process([$pythonExecutable, $pythonScriptPath, $originalFullPath, $redactedFullPath]);
        $process->setTimeout(300); // إعطاء السكربت مهلة دقيقتين كحد أقصى

        try {
            $process->mustRun();

            // إضافة فحص أمان: هل قام البايثون بإنشاء الملف فعلاً؟
            if (file_exists($redactedFullPath)) {
                // إذا وجد الملف، نقوم بتحديث مساره في الداتابيز
                $this->candidate->update([
                    'redacted_cv_path' => $redactedRelativePath
                ]);
            } else {
                // إذا لم يتم إنشاء الملف، نسجل خطأ للمراجعة
                \Log::error('Python script finished, but no file was created at: ' . $redactedFullPath);
            }

        } catch (ProcessFailedException $exception) {
            \Log::error('CV Redaction Failed for Candidate ID: ' . $this->candidate->id . ' - Error: ' . $exception->getMessage());
        }
    }
}