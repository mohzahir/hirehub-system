<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Candidate;
use App\Models\Application;
use App\Jobs\RedactCandidateCv;
use Illuminate\Support\Facades\Http;

class PublicJobPage extends Component
{
    use WithFileUploads;

    public Project $project;
    public $isClosed = false;
    public $success = false;

    // تم تبسيط الحقول لتكون الإيميل والملف فقط!
    public $email, $cv_file;

    public function mount(Project $project)
    {
        $this->project = $project;
        
        if ($this->project->status !== 'open') {
            $this->isClosed = true;
        }
    }

    public function submitApplication()
    {
        if ($this->isClosed) return;

        $this->validate([
            'email' => 'required|email|max:255',
            'cv_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        // زيادة وقت التنفيذ لأن الذكاء الاصطناعي قد يأخذ بضع ثوانٍ
        set_time_limit(120);

        // 1. فحص ما إذا كان المرشح موجوداً مسبقاً بالإيميل
        $candidate = Candidate::where('email', $this->email)->first();

        // 2. إذا كان مرشحاً جديداً، نقرأ ملفه بالذكاء الاصطناعي
        if (!$candidate) {
            $apiKey = env('GEMINI_API_KEY');
            $fileData = base64_encode(file_get_contents($this->cv_file->getRealPath()));
            $mimeType = $this->cv_file->getMimeType();

            $prompt = "قم باستخراج البيانات التالية من هذه السيرة الذاتية ورجعها بصيغة JSON فقط وبدون أي نصوص إضافية. 
            المفاتيح المطلوبة هي:
            first_name (الاسم الأول بالانجليزية), 
            last_name (اسم العائلة بالانجليزية), 
            nationality (الجنسية بالانجليزية), 
            profession (المهنة أو التخصص بالانجليزية), 
            experience_years (سنوات الخبرة كرقم صحيح integer، إذا لم تكن واضحة ضع 0).";

            // قيم افتراضية في حال فشل الذكاء الاصطناعي
            $firstName = 'غير محدد';
            $lastName = 'غير محدد';
            $nationality = 'غير محدد';
            $profession = $this->project->title;
            $experienceYears = 0;

            try {
                $response = Http::withoutVerifying()->timeout(60)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=' . $apiKey, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data' => $fileData
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json',
                    ]
                ]);

                if ($response->successful()) {
                    $geminiResult = $response->json();
                    if (isset($geminiResult['candidates'][0]['content']['parts'][0]['text'])) {
                        $jsonText = $geminiResult['candidates'][0]['content']['parts'][0]['text'];
                        $extractedData = json_decode($jsonText, true);

                        if ($extractedData) {
                            $firstName = $extractedData['first_name'] ?? 'غير محدد';
                            $lastName = $extractedData['last_name'] ?? 'غير محدد';
                            $nationality = $extractedData['nationality'] ?? 'غير محدد';
                            $profession = $extractedData['profession'] ?? $this->project->title;
                            $experienceYears = $extractedData['experience_years'] ?? 0;
                        }
                    }
                }
            } catch (\Exception $e) {
                // إذا فشل الـ AI، لا نوقف التقديم، سنحفظ الملف والإيميل ويمكن للموظف إكمال البيانات لاحقاً
                \Log::error('AI Parsing Error on Public Page: ' . $e->getMessage());
            }

            // حفظ السيرة الذاتية وإنشاء المرشح
            $cvPath = $this->cv_file->store('cvs', 'public');
            
            $candidate = Candidate::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $this->email,
                'nationality' => $nationality,
                'profession' => $profession,
                'experience_years' => $experienceYears,
                'original_cv_path' => $cvPath,
                'partner_id' => null, // تقديم مباشر
            ]);

            // تشغيل الطمس الآلي في الخلفية
            RedactCandidateCv::dispatch($candidate);
        }

        // 3. ربط المرشح بالمشروع
        $existingApp = Application::where('candidate_id', $candidate->id)
                                  ->where('project_id', $this->project->id)
                                  ->exists();
        
        if (!$existingApp) {
            Application::create([
                'candidate_id' => $candidate->id,
                'project_id' => $this->project->id,
                'status' => 'received', 
            ]);
        }

        $this->success = true;
    }

    public function render()
    {
        return view('components.public-job-page')
            ->layout('layouts.portal', ['title' => 'تقديم وظيفة: ' . $this->project->title]);
    }
}