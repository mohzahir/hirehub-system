<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Partner;
use App\Models\Candidate;
use App\Models\Application;
use App\Jobs\RedactCandidateCv;
use Illuminate\Support\Facades\Http;

class PartnerMagicUpload extends Component
{
    use WithFileUploads;

    public Project $project;
    public Partner $partner;
    
    public $batch_cv_files = [];
    public $isProcessed = false;
    
    // متغيرات العدادات
    public $successCount = 0;
    public $skippedCount = 0; // تمت إضافته لمعرفة المكرر
    public $failedCount = 0;

    public function mount(Project $project, Partner $partner)
    {
        $this->project = $project;
        $this->partner = $partner;
    }

    public function processPartnerUploads()
    {
        set_time_limit(300);

        $this->validate([
            'batch_cv_files' => 'required|array|min:1|max:20',
            'batch_cv_files.*' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ], [
            'batch_cv_files.required' => 'الرجاء انتظار اكتمال الرفع قبل الضغط.',
            'batch_cv_files.max' => 'عفواً، الحد الأقصى للدفعة الواحدة هو 20 سيرة ذاتية.',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        
        // تصفير العدادات
        $this->successCount = 0;
        $this->skippedCount = 0;
        $this->failedCount = 0;

        foreach ($this->batch_cv_files as $file) {
            try {
                $fileData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();

                $prompt = "قم باستخراج البيانات التالية من هذه السيرة الذاتية ورجعها بصيغة JSON فقط وبدون أي نصوص إضافية. 
                المفاتيح المطلوبة هي:
                first_name, 
                last_name, 
                email (البريد الإلكتروني، ضع null إذا لم تجده), 
                nationality, 
                profession, 
                experience_years (سنوات الخبرة كرقم صحيح).";

                $response = Http::withoutVerifying()->timeout(60)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=' . $apiKey, [
                    'contents' => [['parts' => [['text' => $prompt], ['inline_data' => ['mime_type' => $mimeType, 'data' => $fileData]]]]],
                    'generationConfig' => ['response_mime_type' => 'application/json']
                ]);

                if ($response->successful()) {
                    $geminiResult = $response->json();
                    if (isset($geminiResult['candidates'][0]['content']['parts'][0]['text'])) {
                        $extractedData = json_decode($geminiResult['candidates'][0]['content']['parts'][0]['text'], true);

                        if ($extractedData) {
                            $firstName = $extractedData['first_name'] ?? 'غير محدد';
                            $lastName = $extractedData['last_name'] ?? 'غير محدد';
                            $nationality = $extractedData['nationality'] ?? 'غير محدد';

                            // 1. الفحص على مستوى السيستم
                            $candidate = Candidate::where('first_name', $firstName)
                                ->where('last_name', $lastName)
                                ->where('nationality', $nationality)
                                ->first();

                            if (!$candidate) {
                                // غير موجود، ننشئ له سجلاً ونحفظ ملفه
                                $cvPath = $file->store('cvs', 'public');
                                $candidate = Candidate::create([
                                    'first_name' => $firstName,
                                    'last_name' => $lastName,
                                    'email' => $extractedData['email'] ?? null,
                                    'nationality' => $nationality,
                                    'profession' => $extractedData['profession'] ?? 'غير محدد',
                                    'experience_years' => $extractedData['experience_years'] ?? 0,
                                    'original_cv_path' => $cvPath,
                                    'partner_id' => $this->partner->id,
                                ]);
                                RedactCandidateCv::dispatch($candidate);
                            }

                            // 2. فحص التكرار في المشروع الحالي
                            $applicationExists = Application::where('candidate_id', $candidate->id)
                                ->where('project_id', $this->project->id)
                                ->exists();

                            if (!$applicationExists) {
                                // إضافته للمشروع
                                Application::create([
                                    'candidate_id' => $candidate->id,
                                    'project_id' => $this->project->id,
                                    'status' => 'received',
                                ]);
                                $this->successCount++;
                            } else {
                                // المرشح مكرر في هذا المشروع، نتخطاه!
                                $this->skippedCount++; 
                            }

                        } else { $this->failedCount++; }
                    } else { $this->failedCount++; }
                } else { $this->failedCount++; }
            } catch (\Exception $e) {
                \Log::error('Partner Upload AI Error: ' . $e->getMessage());
                $this->failedCount++;
            }
        }

        $this->isProcessed = true;
    }

    public function render()
    {
        return view('components.partner-magic-upload')
            ->layout('layouts.portal', ['title' => 'بوابة الموردين - Hirehub']); 
    }
}