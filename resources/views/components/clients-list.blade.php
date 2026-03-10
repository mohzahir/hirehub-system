<div class="p-4 sm:p-6 bg-slate-50 min-h-screen">
    
    @if (session()->has('message'))
        <div x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <span class="font-bold">✔ {{ session('message') }}</span>
            <button @click="show = false" class="text-white">&times;</button>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800">إدارة علاقات العملاء (CRM)</h2>
            <p class="text-slate-500 text-sm mt-1">تتبع أداء المستشفيات والشركات وأرباح المشاريع.</p>
        </div>
        <button wire:click="openModal()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            إضافة عميل جديد
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase font-bold border-b">
                        <th class="p-4">اسم المنشأة / العميل</th>
                        <th class="p-4 text-center">المشاريع</th>
                        <th class="p-4 text-center">إجمالي الأرباح (Net)</th>
                        <th class="p-4">مسؤول التواصل</th>
                        <th class="p-4 text-center">الحالة</th>
                        <th class="p-4 text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($clients as $client)
                        <tr class="hover:bg-blue-50/30 transition">
                            <td class="p-4">
                                <p class="font-bold text-slate-800 text-base">{{ $client->company_name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $client->industry }} | {{ $client->country }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-200">
                                    {{ $client->active_projects_count }} مشاريع
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="text-emerald-600 font-black text-base">
                                    {{ number_format($client->total_revenue) }} <span class="text-[10px]">ريال</span>
                                </span>
                            </td>
                            <td class="p-4 text-sm">
                                <p class="font-semibold text-slate-700">{{ $client->contact_person }}</p>
                                <p class="text-xs text-slate-400">{{ $client->contact_email }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $client->status === 'active' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                    {{ $client->status === 'active' ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="p-4 text-left">
                                <button wire:click="openModal({{ $client->id }})" class="text-blue-600 hover:bg-blue-100 p-2 rounded-lg transition" title="تعديل البيانات">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                <svg class="w-16 h-16 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-5 11h3m-3 4.5V19"></path></svg>
                                لا يوجد عملاء حالياً في نظام الـ CRM.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900 bg-opacity-70 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden border-t-4 border-blue-600">
                <div class="p-6 border-b flex justify-between items-center bg-slate-50">
                    <h3 class="text-xl font-black text-slate-800">{{ $editingClientId ? 'تحديث بيانات العميل' : 'إضافة عميل جديد للنظام' }}</h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-red-500 transition text-3xl">&times;</button>
                </div>

                <form wire:submit.prevent="saveClient" class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">اسم الشركة/المستشفى *</label>
                            <input type="text" wire:model="company_name" class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition bg-slate-50 font-semibold">
                            @error('company_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-bold mb-2">الحالة *</label>
                            <select wire:model="status" class="w-full border-2 border-slate-100 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 transition bg-slate-50 font-bold">
                                <option value="active">نشط (Active)</option>
                                <option value="inactive">غير نشط (Inactive)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                        <div class="sm:col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">الدولة *</label>
                            <input type="text" wire:model="country" class="w-full border-2 border-slate-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 bg-slate-50">
                            @error('country') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">المدينة</label>
                            <input type="text" wire:model="city" class="w-full border-2 border-slate-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 bg-slate-50">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-slate-700 text-sm font-bold mb-2">المجال</label>
                            <input type="text" wire:model="industry" class="w-full border-2 border-slate-100 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-blue-500 bg-slate-50">
                        </div>
                    </div>

                    <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 mb-6">
                        <h4 class="text-blue-800 font-bold mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            معلومات مسؤول التواصل
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-slate-600 text-xs font-bold mb-2">اسم المسؤول *</label>
                                <input type="text" wire:model="contact_person" class="w-full border-0 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 shadow-sm">
                                @error('contact_person') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-slate-600 text-xs font-bold mb-2">البريد الإلكتروني *</label>
                                <input type="email" wire:model="contact_email" class="w-full border-0 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 shadow-sm" dir="ltr">
                                @error('contact_email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-slate-600 text-xs font-bold mb-2">رقم الهاتف</label>
                                <input type="text" wire:model="contact_phone" class="w-full border-0 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 shadow-sm" dir="ltr">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" wire:click="closeModal" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 px-6 rounded-xl transition">إلغاء</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-10 rounded-xl transition shadow-lg">
                            {{ $editingClientId ? 'حفظ التعديلات' : 'إضافة العميل' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>