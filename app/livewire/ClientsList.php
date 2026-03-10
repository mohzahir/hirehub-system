<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;

class ClientsList extends Component
{
    public $showModal = false;
    public $editingClientId = null; // للتفريق بين الإضافة والتعديل

    public $company_name, $country, $city, $industry, $contact_person, $contact_email, $contact_phone, $status;

    public function openModal($clientId = null)
    {
        $this->resetValidation();
        $this->reset(['company_name', 'country', 'city', 'industry', 'contact_person', 'contact_email', 'contact_phone', 'status', 'editingClientId']);

        if ($clientId) {
            $this->editingClientId = $clientId;
            $client = Client::find($clientId);
            $this->company_name = $client->company_name;
            $this->country = $client->country;
            $this->city = $client->city;
            $this->industry = $client->industry;
            $this->contact_person = $client->contact_person;
            $this->contact_email = $client->contact_email;
            $this->contact_phone = $client->contact_phone;
            $this->status = $client->status;
        } else {
            $this->status = 'active';
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function saveClient()
    {
        $rules = [
            'company_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|unique:clients,contact_email,' . $this->editingClientId,
            'contact_phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ];

        $this->validate($rules);

        Client::updateOrCreate(
            ['id' => $this->editingClientId],
            [
                'company_name' => $this->company_name,
                'country' => $this->country,
                'city' => $this->city,
                'industry' => $this->industry,
                'contact_person' => $this->contact_person,
                'contact_email' => $this->contact_email,
                'contact_phone' => $this->contact_phone,
                'status' => $this->status,
            ]
        );

        session()->flash('message', $this->editingClientId ? 'تم تحديث بيانات العميل!' : 'تم إضافة العميل بنجاح!');
        $this->closeModal();
    }

    public function render()
    {
        // جلب العملاء مع عد المشاريع وحساب الأرباح المحصلة
        $clients = Client::with(['projects.applications.placement'])
            ->latest()
            ->get()
            ->map(function ($client) {
                // حساب إجمالي الأرباح الصافية من كل المشاريع
                $client->total_revenue = $client->projects->flatMap->applications
                    ->map->placement
                    ->whereNotNull()
                    ->sum('net_profit');

                // عد المشاريع النشطة
                $client->active_projects_count = $client->projects->count();
                
                return $client;
            });

        return view('components.clients-list', [
            'clients' => $clients
        ])->layout('layouts.app', ['title' => 'إدارة العملاء']);
    }
}