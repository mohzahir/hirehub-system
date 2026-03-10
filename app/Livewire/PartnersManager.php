<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Partner;

class PartnersManager extends Component
{
    public $showModal = false;
    
    // حقول الفورم
    public $partnerId = null;
    public $agency_name, $country, $contact_person, $email, $phone, $commission_agreement;

    protected $rules = [
        'agency_name' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'contact_person' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'commission_agreement' => 'nullable|string',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['partnerId', 'agency_name', 'country', 'contact_person', 'email', 'phone', 'commission_agreement']);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    // جلب بيانات المكتب للتعديل
    public function editPartner($id)
    {
        $partner = Partner::findOrFail($id);
        $this->partnerId = $partner->id;
        $this->agency_name = $partner->agency_name;
        $this->country = $partner->country;
        $this->contact_person = $partner->contact_person;
        $this->email = $partner->email;
        $this->phone = $partner->phone;
        $this->commission_agreement = $partner->commission_agreement;

        $this->showModal = true;
    }

    // حفظ (أو تحديث) المكتب
    public function savePartner()
    {
        $this->validate();

        Partner::updateOrCreate(
            ['id' => $this->partnerId],
            [
                'agency_name' => $this->agency_name,
                'country' => $this->country,
                'contact_person' => $this->contact_person,
                'email' => $this->email,
                'phone' => $this->phone,
                'commission_agreement' => $this->commission_agreement,
            ]
        );

        $this->closeModal();
        session()->flash('message', $this->partnerId ? 'تم تحديث بيانات المكتب بنجاح!' : 'تم إضافة المكتب الشريك بنجاح!');
    }

    // حذف المكتب
    public function deletePartner($id)
    {
        Partner::findOrFail($id)->delete();
        session()->flash('message', 'تم حذف المكتب الشريك من النظام.');
    }

    // تسوية وتصفير الحسابات المالية المعلقة للمكتب الشريك
    public function settlePartnerBalance($partnerId)
    {
        $partner = Partner::findOrFail($partnerId);

        // جلب جميع التعيينات المعلقة الخاصة بهذا المكتب وتحديث حالتها إلى "مدفوعة" (paid)
        $updatedCount = \App\Models\Placement::whereHas('application.candidate', function($q) use ($partnerId) {
            $q->where('partner_id', $partnerId);
        })->where('payment_status', 'pending')
          ->update(['payment_status' => 'paid']);

        if ($updatedCount > 0) {
            session()->flash('message', "💰 تم تسوية الحساب المالي لـ ({$partner->agency_name}) بنجاح! (تم إغلاق وتوثيق {$updatedCount} معاملة)");
        } else {
            session()->flash('error', 'لا توجد معاملات معلقة لتسويتها لهذا المكتب.');
        }
    }

    public function render()
    {
        $partners = Partner::withCount('candidates')->latest()->get();

        // حساب الديون والأرصدة المعلقة لكل مكتب
        foreach ($partners as $partner) {
            // جلب كل التعيينات المعلقة مالياً الخاصة بهذا المكتب
            $pendingPlacements = \App\Models\Placement::whereHas('application.candidate', function($q) use ($partner) {
                $q->where('partner_id', $partner->id);
            })->where('payment_status', 'pending')->get();

            // 1. أموال لدى المكتب الشريك (يجب أن يحولها لنا)
            $partner->owes_hirehub = $pendingPlacements->where('fee_collector', 'partner')->sum('net_profit');
            
            // 2. أموال لدينا (يجب أن نحولها نحن للمكتب الشريك)
            $partner->hirehub_owes = $pendingPlacements->where('fee_collector', 'hirehub')->sum('partner_commission');
            
            // 3. الرصيد الصافي للديون
            $partner->net_balance = $partner->owes_hirehub - $partner->hirehub_owes;
        }

        return view('components.partners-manager', [
            'partners' => $partners
        ])->layout('layouts.app', ['title' => 'إدارة المكاتب الشريكة']);
    }
}