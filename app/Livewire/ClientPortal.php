<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientFeedbackAlert;

class ClientPortal extends Component
{
    public Project $project;
    
    // متغيرات نافذة الرفض
    public $showRejectModal = false;
    public $rejectingAppId = null;
    public $rejection_reason = '';

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    // عندما يضغط العميل "مقبول للمقابلة"
    // عندما يضغط العميل "مقبول للمقابلة"
    public function acceptCandidate($appId)
    {
        $app = Application::where('project_id', $this->project->id)->findOrFail($appId);
        $app->update([
            'status' => 'interviewing', // <--- التعديل هنا (نقلناه لعمود المقابلات)
            'client_feedback' => 'تم القبول من قبل العميل (بانتظار تحديد موعد المقابلة من قبل الوكالة).', // نص أدق
        ]);

        // إرسال تنبيه لإدارة الوكالة
        try {
            // ضع إيميلك أو إيميل الإدارة هنا
            Mail::to('mohzahir@hirehub-sd.com')->send(new ClientFeedbackAlert($app, 'مقبول للمقابلة'));
        } catch (\Exception $e) {
            \Log::error('Admin Alert Email Error: ' . $e->getMessage());
        }
        
        session()->flash('message', 'تم تسجيل قرارك بنجاح. سيقوم فريق Hirehub بجدولة المقابلة قريباً.');
    }

    // فتح نافذة الرفض لكتابة السبب
    public function openRejectModal($appId)
    {
        $this->rejectingAppId = $appId;
        $this->rejection_reason = '';
        $this->showRejectModal = true;
    }

    // تأكيد الرفض مع السبب
    public function confirmRejection()
    {
        $this->validate([
            'rejection_reason' => 'required|string|min:3'
        ], [
            'rejection_reason.required' => 'يرجى كتابة سبب الرفض لمساعدتنا في تحسين الترشيحات القادمة.'
        ]);

        $app = Application::where('project_id', $this->project->id)->findOrFail($this->rejectingAppId);
        $app->update([
            'status' => 'rejected', // ننقله لعمود المرفوضين لديك
            'client_feedback' => 'مرفوض: ' . $this->rejection_reason,
        ]);

        // إرسال تنبيه لإدارة الوكالة
        try {
            // ضع إيميلك أو إيميل الإدارة هنا
            Mail::to('mohzahir@hirehub-sd.com')->send(new ClientFeedbackAlert($app, 'مرفوض'));
        } catch (\Exception $e) {
            \Log::error('Admin Alert Email Error: ' . $e->getMessage());
        }

        $this->showRejectModal = false;
        session()->flash('message', 'تم استبعاد المرشح وتسجيل ملاحظاتك لفريقنا.');
    }

    public function render()
    {
        // جلب المرشحين المرتبطين بهذا المشروع
        $applications = Application::with('candidate')
            ->where('project_id', $this->project->id)
            ->get();

        // المرشحون قيد الانتظار: هم من في عمود "أُرسل للعميل" (سواء أرسلوا بالإيميل أم تم نقلهم يدوياً)
        $pendingApps = $applications->where('status', 'sent_to_client');
        
        // المرشحون الذين تمت مراجعتهم: هم من تفاعل معهم العميل وتم نقلهم لهذه الحالات
        $reviewedApps = $applications->whereIn('status', ['shortlisted', 'interviewing', 'accepted', 'rejected']);

        return view('components.client-portal', [
            'pendingApps' => $pendingApps,
            'reviewedApps' => $reviewedApps,
        ])->layout('layouts.portal', ['title' => 'مراجعة المرشحين - ' . $this->project->client->company_name]);
    }
}