<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Placement;

class FinancialCollections extends Component
{
    // متغيرات نافذة الدفع
    public $showPaymentModal = false;
    public $selectedPlacementId = null;
    public $payment_amount = '';
    public $target_amount = 0; // إجمالي المبلغ المطلوب
    public $already_paid = 0;  // ما تم دفعه مسبقاً
    public $remaining_amount = 0; // المتبقي

    // فتح نافذة تسجيل دفعة مالية
    public function openPaymentModal($id)
    {
        $placement = Placement::findOrFail($id);
        $this->selectedPlacementId = $id;
        $this->already_paid = $placement->amount_paid;
        
        // تحديد المبلغ المطلوب بناءً على من يجمع الرسوم (نحن أم المكتب)
        $this->target_amount = $placement->fee_collector == 'hirehub' ? $placement->total_agency_fee : $placement->net_profit;
        
        $this->remaining_amount = $this->target_amount - $this->already_paid;
        
        // نضع المبلغ المتبقي كقيمة افتراضية في حقل الإدخال لتسهيل العمل
        $this->payment_amount = $this->remaining_amount; 
        
        $this->showPaymentModal = true;
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
    }

    // حفظ الدفعة الجزئية أو الكلية
    public function savePayment()
    {
        $this->validate([
            'payment_amount' => 'required|numeric|min:1|max:' . $this->remaining_amount,
        ], [
            'payment_amount.max' => 'المبلغ المدخل أكبر من الرصيد المتبقي (' . $this->remaining_amount . ' ريال).',
        ]);

        $placement = Placement::findOrFail($this->selectedPlacementId);
        
        // إضافة الدفعة الجديدة للرصيد المدفوع سابقاً
        $newTotalPaid = $placement->amount_paid + $this->payment_amount;
        
        // تحديث السجل.. إذا اكتمل المبلغ، نحول الحالة لـ paid
        $placement->update([
            'amount_paid' => $newTotalPaid,
            'payment_status' => $newTotalPaid >= $this->target_amount ? 'paid' : 'pending'
        ]);
        
        $this->closePaymentModal();
        
        if ($newTotalPaid >= $this->target_amount) {
            session()->flash('message', '🎉 تم سداد المبلغ بالكامل وإغلاق المطالبة المالية!');
        } else {
            session()->flash('message', '💰 تم تسجيل الدفعة الجزئية بنجاح! الرصيد المتبقي هو: ' . ($this->target_amount - $newTotalPaid) . ' ريال.');
        }
    }

    public function render()
    {
        // جلب جميع المطالبات (سواء مدفوعة أو معلقة) لحساب الخزينة بدقة
        $allPlacements = Placement::with(['application.candidate.partner', 'application.project.client'])->get();

        // إجمالي ما دخل الخزينة فعلياً (مجموع كل الدفعات)
        $totalCollected = $allPlacements->sum('amount_paid');

        // المطالبات التي لم تكتمل بعد لعرضها في الجدول
        $pendingCollections = $allPlacements->where('payment_status', 'pending')->sortByDesc('created_at');

        // حساب الديون المتبقية بالسوق
        $totalExpected = 0;
        foreach($pendingCollections as $p) {
            $target = $p->fee_collector == 'hirehub' ? $p->total_agency_fee : $p->net_profit;
            $totalExpected += ($target - $p->amount_paid);
        }

        return view('components.financial-collections', [
            'pendingCollections' => $pendingCollections,
            'totalExpected' => $totalExpected,
            'totalCollected' => $totalCollected
        ])->layout('layouts.app', ['title' => 'الخزينة والتحصيلات']);
    }
}