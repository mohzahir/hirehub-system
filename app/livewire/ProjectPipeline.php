<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // استدعاء ميزة رفع الملفات
use App\Models\Project;
use App\Models\Application;
use App\Models\Candidate;
use App\Jobs\RedactCandidateCv;
use Illuminate\Support\Facades\Mail;
use App\Mail\BatchCvToClient; // تأكد من استدعاء الكلاس الجديد
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Placement;
use Illuminate\Support\Facades\Auth;
use App\Models\Partner; // أضف هذا السطر مع الـ Models الأخرى
use App\Mail\ClientInterviewSchedule;
use App\Mail\CandidateInterviewSchedule; // أضف هذا السطر

class ProjectPipeline extends Component
{
    use WithFileUploads; // تفعيل ميزة رفع الملفات في المكون
    

    public $project;
    
    // متغيرات نافذة إضافة المرشح
    public $showCandidateModal = false;
    public $first_name, $last_name, $email, $nationality, $profession, $experience_years;
    public $cv_file; // لاستقبال ملف الـ CV
    public $notify_candidates = true; // متغير لإرسال الإيميل للمرشحين

    public $showAiBatchModal = false;
    public $batch_cv_files = []; // مصفوفة لاستقبال عدة ملفات

    public $selectedApplications = []; // لتخزين معرفات المرشحين المحددين
    public $showSendBatchModal = false;
    public $ccEmails = ''; // حقل لإدخال إيميلات الـ CC (مفصولين بفاصلة)

    // المتغيرات الجديدة للمراجعة
    public $batchApplicationsData = []; // لتخزين بيانات المرشحين في النافذة
    public $previewApplicationId = null; // الـ ID الخاص بالمرشح المعروض حالياً في الشاشة


    // متغيرات التعديل اليدوي
    public $manual_redacted_cv; 
    public $isUploadingManual = false; // لإظهار/إخفاء حقل الرفع

    // متغيرات التسوية المالية
    public $showFinancialModal = false;
    public $settlingApplicationId = null;
    public $total_fee = '';
    public $partner_fee = 0;
    public $net_profit = 0;
    public $my_commission = 0;
    public $has_partner = false;
    public $partner_name = '';
    public $fee_collector = 'hirehub'; // القيمة الافتراضية


    // متغيرات إضافة مرشحين من قاعدة البيانات
    public $showExistingModal = false;
    public $searchCandidate = ''; // نص البحث
    public $selectedExisting = []; // مصفوفة لتخزين المرشحين المحددين من البحث


    public $filterProjectId = ''; // تخزين الـ ID للمشروع المفلتر
    public $filterStatus = '';    // تخزين الحالة المفلترة (مثلاً: الفرز المبدئي)


    public $partner_id = null; // لاختيار الشريك في الإضافة اليدوية
    public $batch_partner_id = null; // لاختيار الشريك في الإضافة بالذكاء الاصطناعي

    public $magicLink = '';

    public $clientMagicLink = '';

    public $notify_client = true; // افتراضياً، نعم قم بمراسلة العميل

    // متغيرات جدولة المقابلات
    public $showInterviewModal = false;
    public $interview_date = '';
    public $interview_time = '';
    public $interview_link = '';

    // الحالات المتاحة
    public $statuses = [
        'received' => 'تم الاستلام',
        'shortlisted' => 'الفرز المبدئي',
        'sent_to_client' => 'أُرسل للعميل',
        'interviewing' => 'مرحلة المقابلات',
        'accepted' => 'تم القبول',
        'rejected' => 'مرفوض',
    ];

    public function mount($id)
    {
        // $this->project = Project::with(['applications.candidate'])->findOrFail($id);
        $this->project = Project::with(['applications.candidate.partner'])->findOrFail($id);
    }

    public function changeStatus($applicationId, $newStatus)
    {
        // إذا كانت الحالة "تم القبول"، نوقف النقل المباشر ونفتح نافذة التسوية!
        if ($newStatus === 'accepted') {
            $this->openFinancialModal($applicationId);
            return; 
        }

        $application = Application::find($applicationId);
        if ($application && array_key_exists($newStatus, $this->statuses)) {
            $application->update(['status' => $newStatus]);
            $this->project->refresh();
        }
    }


    public function generateClientLink()
    {
        $this->clientMagicLink = \Illuminate\Support\Facades\URL::signedRoute('client.portal', [
            'project' => $this->project->id,
        ]);
    }


    // فتح نافذة البحث في قاعدة البيانات
    public function openExistingModal()
    {
        $this->searchCandidate = '';
        $this->filterProjectId = '';
        $this->filterStatus = '';
        $this->selectedExisting = [];
        $this->showExistingModal = true;
    }

    public function closeExistingModal()
    {
        $this->showExistingModal = false;
    }

    // حفظ المرشحين المحددين في المشروع
    public function addExistingToProject()
    {
        if (empty($this->selectedExisting)) {
            session()->flash('error', 'الرجاء تحديد مرشح واحد على الأقل.');
            return;
        }

        foreach ($this->selectedExisting as $candidateId) {
            // فحص أمان إضافي: التأكد من أن المرشح ليس في المشروع بالفعل
            $exists = Application::where('candidate_id', $candidateId)
                                 ->where('project_id', $this->project->id)
                                 ->exists();
            if (!$exists) {
                Application::create([
                    'candidate_id' => $candidateId,
                    'project_id' => $this->project->id,
                    'status' => 'received', // ينزلون في أول عمود
                ]);
            }
        }

        $this->project->refresh();
        $this->closeExistingModal();
        session()->flash('message', 'تم استيراد ' . count($this->selectedExisting) . ' مرشحين للمشروع بنجاح!');
    }


    // فتح حقل الرفع اليدوي
    public function toggleManualUpload()
    {
        $this->isUploadingManual = !$this->isUploadingManual;
    }

    // حفظ الملف المظلل يدوياً واستبدال القديم
    public function saveManualRedaction()
    {
        $this->validate([
            'manual_redacted_cv' => 'required|file|mimes:pdf|max:5120',
        ]);

        $application = Application::with('candidate')->find($this->previewApplicationId);

        if ($application) {
            // رفع الملف الجديد
            $newPath = $this->manual_redacted_cv->store('cvs/redacted', 'public');

            // تحديث مسار الملف المظلل في قاعدة البيانات
            $application->candidate->update([
                'redacted_cv_path' => $newPath
            ]);

            // إعادة تعيين المتغيرات لتحديث واجهة العرض
            $this->isUploadingManual = false;
            $this->manual_redacted_cv = null;
            
            // تحديث بيانات المرشحين في النافذة ليرى المستخدم الملف الجديد فوراً
            $this->batchApplicationsData = Application::with('candidate')
                ->whereIn('id', $this->selectedApplications)
                ->get();

            session()->flash('message', 'تم استبدال السيرة الذاتية بالنسخة المعدلة يدوياً بنجاح!');
        }
    }


    // إزالة المرشح من المشروع الحالي (لا يحذفه من قاعدة البيانات الكلية)
    public function removeCandidate($applicationId)
    {
        $application = Application::find($applicationId);
        
        if ($application) {
            // حماية مالية: نمنع حذف المرشح إذا تم توظيفه وتمت تسويته المالية
            if ($application->status === 'accepted') {
                session()->flash('error', 'لا يمكن حذف مرشح تم توظيفه بالفعل. يجب إلغاء التسوية المالية أولاً.');
                return;
            }

            // حذف الارتباط (Application)
            $application->delete();
            
            // إزالة التحديد عنه إذا كان محدداً في مصفوفة الإرسال الجماعي
            $this->selectedApplications = array_diff($this->selectedApplications, [$applicationId]);
            
            $this->project->refresh();
            session()->flash('message', 'تم إزالة المرشح من هذا المشروع بنجاح (لا يزال محفوظاً في النظام).');
        }
    }


    // فتح نافذة الرفع الجماعي بالذكاء الاصطناعي
    public function openAiBatchModal()
    {
        $this->resetValidation();
        // أضفنا batch_partner_id هنا
        $this->batch_cv_files = [];
        $this->batch_partner_id = null; 
        $this->showAiBatchModal = true;
    }

    public function generateMagicLink()
    {
        $this->validate([
            'batch_partner_id' => 'required'
        ], [
            'batch_partner_id.required' => 'الرجاء اختيار المكتب الشريك أولاً من القائمة أعلاه لتوليد رابطه السري.'
        ]);

        // توليد رابط مشفر يحمل توقيع لارافيل (آمن جداً)
        $this->magicLink = \Illuminate\Support\Facades\URL::signedRoute('magic.upload', [
            'project' => $this->project->id,
            'partner' => $this->batch_partner_id,
        ]);
    }

    public function closeAiBatchModal()
    {
        $this->showAiBatchModal = false;
    }

    

    // معالجة الملفات بالذكاء الاصطناعي
    public function processAiBatch()
    {
        // 1. زيادة وقت التنفيذ المسموح به إلى 5 دقائق (300 ثانية) لتجنب انقطاع العملية
        set_time_limit(300);

        $this->validate([
            'batch_cv_files' => 'required|array|min:1',
            'batch_cv_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'batch_cv_files.required' => 'الرجاء انتظار اكتمال رفع الملفات قبل الضغط على بدء التحليل.',
            'batch_cv_files.array' => 'حدث خطأ في قراءة الملفات.',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        $successCount = 0;
        $skippedCount = 0;
        $failedCount = 0; // لمعرفة عدد الملفات التي لم يتم التعرف عليها

        foreach ($this->batch_cv_files as $file) {
            try {
                $fileData = base64_encode(file_get_contents($file->getRealPath()));
                $mimeType = $file->getMimeType();

                $prompt = "قم باستخراج البيانات التالية من هذه السيرة الذاتية ورجعها بصيغة JSON فقط وبدون أي نصوص إضافية. 
                المفاتيح المطلوبة هي:
                first_name (الاسم الأول بالانجليزية), 
                last_name (اسم العائلة بالانجليزية), 
                email (ضعها null إذا لم تجدها),
                nationality (الجنسية بالانجليزية), 
                profession (المهنة أو التخصص بالانجليزية), 
                experience_years (سنوات الخبرة كرقم صحيح integer، إذا لم تكن واضحة ضع 0).";

                // 2. إضافة حد زمني (timeout) محدد للاتصال بالـ API لكي لا يعلق النظام
                // قم بتعديل هذا الكود:
                $response = Http::withoutVerifying()->timeout(60)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-3.1-flash-lite-preview:generateContent?key=' . $apiKey, [
                    // ... باقي الكود كما هو
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

                            // 1. هل المرشح الذي قرأه الذكاء الاصطناعي موجود مسبقاً في السيستم؟
                            $candidate = \App\Models\Candidate::where('first_name', $firstName)
                                ->where('last_name', $lastName)
                                ->where('nationality', $nationality)
                                ->first();

                            if (!$candidate) {
                                // غير موجود، ننشئ له سجلاً ونحفظ ملفه
                                $cvPath = $file->store('cvs', 'public');
                                $candidate = \App\Models\Candidate::create([
                                    'first_name' => $firstName,
                                    'last_name' => $lastName,
                                    'email' => $extractedData['email'] ?? null,
                                    'nationality' => $nationality,
                                    'profession' => $extractedData['profession'] ?? 'غير محدد',
                                    'experience_years' => $extractedData['experience_years'] ?? 0,
                                    'original_cv_path' => $cvPath,
                                    'partner_id' => $this->batch_partner_id ?: null, // السطر الجديد
                                ]);
                                \App\Jobs\RedactCandidateCv::dispatch($candidate);
                            }

                            // 2. فحص التكرار في المشروع الحالي
                            $applicationExists = \App\Models\Application::where('candidate_id', $candidate->id)
                                ->where('project_id', $this->project->id)
                                ->exists();

                            if (!$applicationExists) {
                                // إضافته للمشروع
                                \App\Models\Application::create([
                                    'candidate_id' => $candidate->id,
                                    'project_id' => $this->project->id,
                                    'status' => 'received',
                                ]);
                                $successCount++;
                            } else {
                                // المرشح مكرر في هذا المشروع، نتخطاه!
                                // تأكد من تعريف هذا المتغير $skippedCount = 0; خارج حلقة foreach
                                $skippedCount++; 
                            }
                        } else {
                            $failedCount++;
                        }
                    } else {
                        $failedCount++;
                    }
                } else {
                    $failedCount++;
                }
            } catch (\Exception $e) {
                // إذا حدث أي خطأ في ملف معين (مثل كبر حجمه)، يتم تسجيل الخطأ وتخطي الملف للذي بعده
                \Log::error('AI Batch Process Error for a file: ' . $e->getMessage());
                $failedCount++;
            }
        }

        $this->project->refresh();
        
        $msg = "تم إضافة ($successCount) مرشح بنجاح.";
        if ($skippedCount > 0) $msg .= " وتم تخطي ($skippedCount) مرشح لأنهم موجودون مسبقاً.";
        if ($failedCount > 0) $msg .= " وتعذر قراءة ($failedCount) ملف.";
        
        session()->flash('message', $msg);
        $this->closeAiBatchModal();
    }



    // فتح نافذة الإرسال والمراجعة الجماعية
    public function openSendBatchModal()
    {
        if (empty($this->selectedApplications)) {
            session()->flash('error', 'الرجاء تحديد مرشح واحد على الأقل.');
            return;
        }

        $this->ccEmails = ''; 
        
        // جلب بيانات المرشحين المحددين
        $this->batchApplicationsData = Application::with('candidate')
            ->whereIn('id', $this->selectedApplications)
            ->get();

        // تعيين أول مرشح في القائمة ليكون المعروض افتراضياً في شاشة المراجعة
        if ($this->batchApplicationsData->count() > 0) {
            $this->previewApplicationId = $this->batchApplicationsData->first()->id;
        }

        $this->showSendBatchModal = true;
    }

    // دالة لتغيير السيرة الذاتية المعروضة في الشاشة عند الضغط على اسم مرشح آخر
    public function setPreview($applicationId)
    {
        $this->previewApplicationId = $applicationId;
    }

    public function closeSendBatchModal()
    {
        $this->showSendBatchModal = false;
        $this->batchApplicationsData = [];
        $this->previewApplicationId = null;
    }

    // فتح نافذة الجدولة الجماعية
    public function openInterviewModal()
    {
        if (empty($this->selectedApplications)) {
            session()->flash('error', 'الرجاء تحديد مرشح واحد على الأقل لجدولة المقابلة.');
            return;
        }

        $this->reset(['interview_date', 'interview_time', 'interview_link']);
        $this->showInterviewModal = true;
    }

    // فتح نافذة الجدولة لتعديل موعد مرشح واحد
    public function editInterview($applicationId)
    {
        $app = Application::find($applicationId);
        
        if ($app) {
            // تحديد هذا المرشح فقط برمجياً
            $this->selectedApplications = [$applicationId];
            
            // جلب الموعد القديم ووضعه في الحقول
            $this->interview_date = $app->interview_date;
            $this->interview_time = $app->interview_time;
            $this->interview_link = $app->interview_link;
            
            // فتح نافذة الجدولة
            $this->showInterviewModal = true;
        }
    }

    public function closeInterviewModal()
    {
        $this->showInterviewModal = false;
    }

    // حفظ تفاصيل المقابلة للمرشحين المحددين
    public function scheduleInterviews()
    {
        $this->validate([
            'interview_date' => 'required|date',
            'interview_time' => 'required',
            'interview_link' => 'nullable|string|max:255',
        ]);

        $updatedApps = Application::with('candidate')->whereIn('id', $this->selectedApplications)->get();

        Application::whereIn('id', $this->selectedApplications)->update([
            'status' => 'interviewing',
            'interview_date' => $this->interview_date,
            'interview_time' => $this->interview_time,
            'interview_link' => $this->interview_link,
        ]);

        // إرسال الإيميل للعميل
        if ($this->notify_client && $this->project->client && $this->project->client->contact_email) {
            try {
                $candidatesData = $updatedApps->pluck('candidate'); 
                Mail::to($this->project->client->contact_email)
                    ->send(new ClientInterviewSchedule($this->project, $candidatesData, $this->interview_date, $this->interview_time, $this->interview_link));
            } catch (\Exception $e) {
                \Log::error('Client Email Error: ' . $e->getMessage());
            }
        }

        // إرسال الإيميل للمرشحين فرادى
        if ($this->notify_candidates) {
            foreach ($updatedApps as $app) {
                if ($app->candidate->email) {
                    try {
                        Mail::to($app->candidate->email)
                            ->send(new CandidateInterviewSchedule($this->project, $app->candidate, $this->interview_date, $this->interview_time, $this->interview_link));
                    } catch (\Exception $e) {
                        \Log::error('Candidate Email Error: ' . $e->getMessage());
                    }
                }
            }
        }

        $count = count($this->selectedApplications);
        $this->project->refresh();
        $this->selectedApplications = []; 
        $this->closeInterviewModal();
        
        session()->flash('message', "تمت جدولة المقابلة وإرسال الإشعارات بنجاح!");
    }

    // دالة الإرسال الجماعي (تبقى كما هي تقريباً مع تعديل بسيط)
    public function sendBatchToClient()
    {
        $applications = Application::with('candidate')->whereIn('id', $this->selectedApplications)->get();
        $clientEmail = $this->project->client->contact_email;
        $ccArray = array_filter(array_map('trim', explode(',', $this->ccEmails)));

        // فحص أمان أخير: التأكد من أن جميع المحددين لديهم ملفات مظللة جاهزة
        foreach ($applications as $app) {
            if (!$app->candidate->redacted_cv_path) {
                session()->flash('error', 'عفواً، المرشح (' . $app->candidate->first_name . ') لم تكتمل معالجة سيرته الذاتية بعد. يرجى الانتظار أو إزالته من التحديد.');
                return;
            }
        }

        try {
            Mail::to($clientEmail)
                ->cc($ccArray)
                ->send(new BatchCvToClient($applications, $this->project));

            foreach ($applications as $app) {
                $app->update([
                    'status' => 'sent_to_client',
                    'is_sent' => true
                ]);
            }

            session()->flash('message', 'تم مراجعة وإرسال ' . $applications->count() . ' سير ذاتية للعميل بنجاح!');
            
            $this->selectedApplications = [];
            $this->closeSendBatchModal();

        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء إرسال البريد: ' . $e->getMessage());
        }
    }

    // دوال التحكم في نافذة المرشح
    public function openCandidateModal()
    {
        $this->resetValidation();
        // أضفنا partner_id هنا
        $this->reset(['first_name', 'last_name', 'email', 'nationality', 'profession', 'experience_years', 'cv_file', 'partner_id']);
        $this->showCandidateModal = true;
    }

    public function closeCandidateModal()
    {
        $this->showCandidateModal = false;
    }

    // دالة حفظ المرشح الجديد
    public function saveCandidate()
    {
        $this->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'partner_id' => 'nullable|exists:partners,id',
            'nationality' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // 1. الفحص على مستوى السيستم: هل المرشح موجود مسبقاً؟
        $candidate = Candidate::where('first_name', $this->first_name)
                              ->where('last_name', $this->last_name)
                              ->where('nationality', $this->nationality)
                              ->first();

        if ($candidate) {
            // المرشح موجود في السيستم! الآن نفحص هل هو موجود في هذا المشروع؟
            $existsInProject = Application::where('candidate_id', $candidate->id)
                                          ->where('project_id', $this->project->id)
                                          ->exists();
            if ($existsInProject) {
                session()->flash('error', 'عفواً، هذا المرشح موجود بالفعل في هذا المشروع!');
                return;
            }

            // إذا كان في السيستم لكن ليس في المشروع، نربطه بالمشروع فقط بدون رفع CV جديد
            Application::create([
                'candidate_id' => $candidate->id,
                'project_id' => $this->project->id,
                'status' => 'received',
            ]);

            $this->project->refresh();
            session()->flash('message', 'المرشح موجود مسبقاً في النظام، تم ربطه بهذا المشروع بنجاح!');
            $this->closeCandidateModal();
            return;
        }

        // 2. إذا لم يكن موجوداً، نقوم بإنشائه كمرشح جديد (الكود الطبيعي)
        $cvPath = $this->cv_file->store('cvs', 'public');

        $candidate = Candidate::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'nationality' => $this->nationality,
            'profession' => $this->profession,
            'experience_years' => $this->experience_years,
            'original_cv_path' => $cvPath,
            'partner_id' => $this->partner_id ?: null, // السطر الجديد
        ]);

        RedactCandidateCv::dispatch($candidate);

        Application::create([
            'candidate_id' => $candidate->id,
            'project_id' => $this->project->id,
            'status' => 'received',
        ]);

        $this->project->refresh();
        session()->flash('message', 'تم إضافة المرشح الجديد ورفع السيرة الذاتية بنجاح!');
        $this->closeCandidateModal();
    }


    // فتح نافذة التسوية
    public function openFinancialModal($applicationId)
    {
        $this->fee_collector = 'hirehub';

        // جلب بيانات المرشح مع تفاصيل المكتب الشريك (إن وجد)
        $app = Application::with('candidate.partner')->find($applicationId);
        $this->settlingApplicationId = $applicationId;
        
        $this->has_partner = $app->candidate->partner_id !== null;
        $this->partner_name = $this->has_partner ? $app->candidate->partner->agency_name : '';

        // تصفير الحقول
        $this->total_fee = '';
        $this->partner_fee = 0;
        $this->net_profit = 0;
        $this->my_commission = 0;

        $this->showFinancialModal = true;
    }

    // هذه الدوال المدمجة في Livewire تعمل تلقائياً عند كتابة أي رقم في الحقول لحساب النواتج فوراً
    public function updatedTotalFee($value) { $this->calculateFinancials(); }
    public function updatedPartnerFee($value) { $this->calculateFinancials(); }

    public function calculateFinancials()
    {
        $total = (float) $this->total_fee;
        $partner = (float) $this->partner_fee;

        // حساب صافي أرباح الوكالة
        $this->net_profit = max(0, $total - $partner);

        // جلب نسبة عمولة الموظف الحالي (أنت أو صفاء) من قاعدة البيانات، أو 5% كافتراضي
        $rate = auth()->user()->commission_rate ?? 5.00;
        
        // حساب عمولتك الشخصية من الصافي
        $this->my_commission = $this->net_profit * ($rate / 100);
    }

    // حفظ التسوية وإغلاق الشاغر
    public function saveSettlement()
    {
        $this->validate([
            'total_fee' => 'required|numeric|min:0',
            'partner_fee' => 'required|numeric|min:0',
        ]);

        $app = Application::find($this->settlingApplicationId);

        // 1. إنشاء سجل مالي في جدول Placements
        Placement::create([
            'application_id' => $app->id,
            'fee_collector' => $this->fee_collector, // <--- السطر الجديد
            'total_agency_fee' => $this->total_fee,
            'partner_commission' => $this->partner_fee,
            'recruiter_commission' => $this->my_commission,
            'net_profit' => $this->net_profit,
            'payment_status' => 'pending',
        ]);

        // 2. تحديث حالة المرشح إلى "تم القبول" فعلياً في قاعدة البيانات
        $app->update(['status' => 'accepted']);

        // 3. تحديث الواجهة وعرض رسالة التهنئة
        $this->project->refresh();
        $this->showFinancialModal = false;
        
        session()->flash('message', '🎉 مبروك! تم توظيف المرشح بنجاح، وتم تسجيل عمولتك ('.$this->my_commission.' ريال) في حسابك.');
    }

    public function closeFinancialModal()
    {
        $this->showFinancialModal = false;
        $this->settlingApplicationId = null;
        // إعادة تحميل المشروع لكي يرجع المؤشر في القائمة المنسدلة للحالة القديمة بدلاً من "تم القبول"
        $this->project->refresh(); 
    }


    public function render()
    {
        // 1. جلب IDs المرشحين الموجودين في هذا المشروع حالياً لاستثنائهم
        $existingCandidateIds = $this->project->applications()->pluck('candidate_id')->toArray();

        // 2. جلب كل المشاريع السابقة لعرضها في القائمة المنسدلة للفلترة
        $otherProjects = Project::with('client')->where('id', '!=', $this->project->id)->latest()->get();

        // 3. البحث الذكي المدمج في جدول المرشحين
        $availableCandidates = Candidate::query()
            ->whereNotIn('id', $existingCandidateIds)
            
            // الفلتر 1: البحث النصي
            ->when($this->searchCandidate, function($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%'.$this->searchCandidate.'%')
                      ->orWhere('last_name', 'like', '%'.$this->searchCandidate.'%')
                      ->orWhere('profession', 'like', '%'.$this->searchCandidate.'%')
                      ->orWhere('nationality', 'like', '%'.$this->searchCandidate.'%');
                });
            })
            
            // الفلتر 2 و 3: البحث حسب المشروع المختار و/أو الحالة
            ->when($this->filterProjectId || $this->filterStatus, function($query) {
                $query->whereHas('applications', function($q) {
                    if ($this->filterProjectId) {
                        $q->where('project_id', $this->filterProjectId);
                    }
                    if ($this->filterStatus) {
                        $q->where('status', $this->filterStatus);
                    }
                });
            })
            ->limit(20) // رفعنا الحد إلى 20 لأننا أصبحنا نفلتر بدقة
            ->get();

        $partners = Partner::all(); // جلب كل المكاتب الشريكة

        return view('components.project-pipeline', [
            'availableCandidates' => $availableCandidates,
            'otherProjects' => $otherProjects, // نرسل المشاريع السابقة للواجهة
            'partners' => $partners // إرسال المكاتب للواجهة
        ])->layout('layouts.app', ['title' => 'مسار المرشحين - ' . $this->project->title]);
    }
}