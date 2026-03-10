<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    // المشروع يتبع لعميل
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // المشروع لديه عدة مرشحين (تطبيقات)
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}