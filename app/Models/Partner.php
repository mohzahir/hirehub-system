<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $guarded = [];

    // المكتب الشريك يجلب عدة مرشحين
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}