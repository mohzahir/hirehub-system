<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Client;
use App\Models\User; // استدعاء موديل الموظفين

class ProjectsList extends Component
{
    public $showModal = false;
    public $editingProjectId = null; // لمعرفة هل نحن نضيف أم نعدل

    // حقول الفورم
    public $title, $client_id, $user_id, $required_count = 1, $offered_salary, $description;

    // دالة الفتح (تستخدم للإضافة أو التعديل بناءً على إرسال الـ ID)
    public function openModal($projectId = null)
    {
        $this->resetValidation();
        $this->reset(['title', 'client_id', 'user_id', 'required_count', 'offered_salary', 'description', 'editingProjectId']);

        if ($projectId) {
            // وضع التعديل: جلب بيانات المشروع القديمة
            $this->editingProjectId = $projectId;
            $project = Project::findOrFail($projectId);
            
            $this->title = $project->title;
            $this->client_id = $project->client_id;
            $this->user_id = $project->user_id;
            $this->required_count = $project->required_count;
            $this->offered_salary = $project->offered_salary;
            $this->description = $project->description;
        } else {
            // وضع الإضافة: جعل الموظف الافتراضي هو المستخدم الحالي
            $this->user_id = auth()->id();
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveProject()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'user_id' => 'required|exists:users,id', // تحقق من أن الموظف موجود
            'required_count' => 'required|integer|min:1',
            'offered_salary' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $data = [
            'title' => $this->title,
            'client_id' => $this->client_id,
            'user_id' => $this->user_id,
            'required_count' => $this->required_count,
            'offered_salary' => $this->offered_salary,
            'description' => $this->description,
        ];

        // إذا كان مشروعاً جديداً، نضيف حالته كـ 'مفتوح'
        if (!$this->editingProjectId) {
            $data['status'] = 'open';
        }

        // التحديث أو الإنشاء
        Project::updateOrCreate(
            ['id' => $this->editingProjectId],
            $data
        );

        $this->closeModal();
        session()->flash('message', $this->editingProjectId ? 'تم تعديل بيانات المشروع بنجاح!' : 'تم إضافة المشروع بنجاح وتعيين المسؤول!');
    }

    // دالة لحذف المشروع
    public function deleteProject($id)
    {
        Project::findOrFail($id)->delete();
        session()->flash('message', 'تم حذف المشروع بنجاح.');
    }

    public function render()
    {
        // جلب المشاريع مع العميل والموظف المسؤول
        $projects = Project::with(['client', 'user'])->latest()->get(); 
        $clients = Client::all();
        $users = User::all(); // جلب قائمة الموظفين (المستخدمين)

        return view('components.projects-list', [
            'projects' => $projects,
            'clients' => $clients,
            'users' => $users // إرسال المستخدمين للواجهة
        ])->layout('layouts.app', ['title' => 'المشاريع والشواغر']);
    }
}