<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'ai_summary' => 'array',
    ];

    // المرشح قد يتبع لمكتب شريك
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    // المرشح لديه عدة طلبات تقديم في مشاريع مختلفة
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}