<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    // العميل لديه عدة مشاريع
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // علاقة متقدمة لجلب كل التسويات المالية المرتبطة بمشاريع هذا العميل
    public function placements()
    {
        return $this->hasManyThrough(
            \App\Models\Placement::class,
            \App\Models\Application::class,
            'id', // ليس مستخدماً مباشرة هنا بل عبر المشروع
            'application_id',
            'id',
            'id'
        )->join('projects', 'applications.project_id', '=', 'projects.id')
        ->where('projects.client_id', $this->id);
    }
}