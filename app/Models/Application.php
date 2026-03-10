<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $guarded = [];

    // التقديم يخص مرشح معين
    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    // التقديم يخص مشروع معين
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // التقديم الناجح يرتبط بعملية مالية واحدة
    public function placement()
    {
        return $this->hasOne(Placement::class);
    }
}