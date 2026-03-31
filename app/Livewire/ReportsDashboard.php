<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsDashboard extends Component
{
    // متغيرات تقرير المشروع
    public $selectedProjectId = '';
    
    // متغيرات تقرير العميل
    public $selectedClientId = '';

    // 1. دالة تحميل تقرير المشروع (القديمة)
    public function downloadProjectReport()
    {
        $this->validate([
            'selectedProjectId' => 'required|exists:projects,id'
        ], [
            'selectedProjectId.required' => 'الرجاء اختيار مشروع من القائمة أولاً.'
        ]);

        $project = Project::with(['client', 'applications.candidate'])->findOrFail($this->selectedProjectId);

        $pdf = Pdf::loadView('reports.project_report', ['project' => $project]);
        $pdf->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Project_Status_Report_' . date('Y-m-d') . '.pdf');
    }

    // 2. دالة تحميل تقرير العميل الشامل (الجديدة)
    public function downloadClientReport()
    {
        $this->validate([
            'selectedClientId' => 'required|exists:clients,id'
        ], [
            'selectedClientId.required' => 'الرجاء اختيار عميل (مستشفى) من القائمة أولاً.'
        ]);

        // جلب العميل مع جميع مشاريعه، وداخل كل مشروع نجلب المرشحين
        $client = Client::with(['projects.applications.candidate'])->findOrFail($this->selectedClientId);

        $pdf = Pdf::loadView('reports.client_report', ['client' => $client]);
        $pdf->setPaper('A4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Client_Comprehensive_Report_' . date('Y-m-d') . '.pdf');
    }

    public function render()
    {
        // جلب البيانات للقوائم المنسدلة
        $projects = Project::with('client')->latest()->get();
        $clients = Client::latest()->get();
        
        return view('components.reports-dashboard', [
            'projects' => $projects,
            'clients' => $clients
        ])->layout('layouts.app', ['title' => 'لوحة التقارير']);
    }
}