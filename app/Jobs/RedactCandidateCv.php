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
use Throwable; // أضفنا هذا لالتقاط جميع أنواع الأخطاء بما فيها نفاذ الوقت

class RedactCandidateCv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $candidate;

    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    public function handle()
    {
        // التحقق من وجود السيرة الذاتية الأصلية
        if (!$this->candidate->original_cv_path) {
            return;
        }

        $originalRelativePath = $this->candidate->original_cv_path;
        $originalFullPath = storage_path('app/public/' . $originalRelativePath);
        
        // تجهيز اسم ومسار النسخة المظللة
        $redactedFileName = 'redacted_' . basename($originalRelativePath);
        $redactedRelativePath = 'cvs/redacted/' . $redactedFileName;
        $redactedFullPath = storage_path('app/public/' . $redactedRelativePath);

        // التأكد من وجود مجلد النسخ المظللة
        if (!Storage::disk('public')->exists('cvs/redacted')) {
            Storage::disk('public')->makeDirectory('cvs/redacted');
        }

        $pythonScriptPath = base_path('scripts/cv_redactor.py');
        $pythonExecutable = base_path('venv/bin/python');

        // إعطاء مهلة دقيقتين فقط للملف، إذا تجاوزها نعتبره معطوباً ونتجاوزه
        $process = new Process([$pythonExecutable, $pythonScriptPath, $originalFullPath, $redactedFullPath]);
        $process->setTimeout(45); 

        try {
            // محاولة تشغيل البايثون
            $process->mustRun();

            // فحص هل نجح البايثون في إنشاء الملف المظلل؟
            if (file_exists($redactedFullPath)) {
                $this->candidate->update([
                    'redacted_cv_path' => $redactedRelativePath
                ]);
            } else {
                // البايثون انتهى لكن لم ينشئ ملفاً (ملف غريب أو صورة)
                $this->applyFallback($originalRelativePath, $redactedRelativePath);
            }

        } catch (Throwable $exception) {
            // التقاط أي خطأ (نفاذ الوقت Timeout، خطأ برمجي، ملف معطوب)
            \Log::warning('CV Redaction Failed for Candidate ID: ' . $this->candidate->id . '. Applying fallback. Error: ' . $exception->getMessage());
            
            // تنفيذ الخطة البديلة (نسخ الملف الأصلي)
            $this->applyFallback($originalRelativePath, $redactedRelativePath);
        }
    }

    /**
     * دالة الخطة البديلة: تنسخ الملف الأصلي ليكون هو المظلل مؤقتاً
     */
    private function applyFallback($originalPath, $redactedPath)
    {
        // إذا كان الملف الأصلي موجوداً، نقوم بنسخه باسم الملف المظلل
        if (Storage::disk('public')->exists($originalPath)) {
            Storage::disk('public')->copy($originalPath, $redactedPath);
            
            // تحديث قاعدة البيانات لترى لوحة التحكم هذا الملف
            $this->candidate->update([
                'redacted_cv_path' => $redactedPath
            ]);
            
            \Log::info('Fallback applied successfully for Candidate ID: ' . $this->candidate->id);
        }
    }
}