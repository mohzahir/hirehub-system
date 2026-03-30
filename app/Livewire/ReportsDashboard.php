<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsDashboard extends Component
{
    public $selectedProjectId = '';

    public function downloadReport()
    {
        $this->validate([
            'selectedProjectId' => 'required|exists:projects,id'
        ], [
            'selectedProjectId.required' => 'الرجاء اختيار مشروع من القائمة أولاً.'
        ]);

        // جلب بيانات المشروع مع العميل والمرشحين
        $project = Project::with(['client', 'applications.candidate'])->findOrFail($this->selectedProjectId);

        // إعداد خيارات الـ PDF
        $pdf = Pdf::loadView('reports.project_report', ['project' => $project]);
        $pdf->setPaper('A4', 'landscape'); // جعل الصفحة بالعرض لتستوعب الجدول المليء بالبيانات

        // تنزيل الملف مباشرة
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Project_Status_Report_' . date('Y-m-d') . '.pdf');
    }

    public function render()
    {
        // جلب المشاريع لعرضها في القائمة المنسدلة
        $projects = Project::with('client')->latest()->get();
        
        return view('components.reports-dashboard', [
            'projects' => $projects
        ])->layout('layouts.app', ['title' => 'لوحة التقارير']);
    }
}