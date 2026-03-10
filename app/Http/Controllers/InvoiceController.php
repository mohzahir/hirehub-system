<?php

namespace App\Http\Controllers;

use App\Models\Placement;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($id)
    {
        // جلب بيانات التوظيف مع كافة العلاقات (المرشح، المكتب، العميل)
        $placement = Placement::with(['application.candidate.partner', 'application.project.client'])->findOrFail($id);
        
        return view('invoice.print', compact('placement'));
    }
}