<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Placement extends Model
{
    use HasFactory;

    protected $guarded = [];

    // العملية المالية تتبع لطلب تقديم
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}