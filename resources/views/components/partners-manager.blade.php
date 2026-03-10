<div class="p-4 sm:p-6 space-y-6">

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                شبكة المكاتب الشريكة
            </h2>
            <p class="text-sm text-gray-500 mt-1">أدر علاقاتك، عقودك، وعمولاتك مع الموردين الخارجيين.</p>
        </div>
        <button wire:click="openModal" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-5 rounded-lg transition shadow-md flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            تسجيل مكتب جديد
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-sm border-b">
                        <th class="p-4 font-bold">اسم المكتب / الوكالة</th>
                        <th class="p-4 font-bold">الدولة</th>
                        <th class="p-4 font-bold">الشخص المسؤول (Contact)</th>
                        <th class="p-4 font-bold">التواصل</th>
                        <th class="p-4 font-bold text-center">عدد المرشحين</th>
                        <th class="p-4 font-bold text-center">الرصيد المالي المعلق</th>
                        <th class="p-4 font-bold text-left">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($partners as $partner)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-bold text-gray-800">{{ $partner->agency_name }}</td>
                            <td class="p-4">
                                <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2 py-1 rounded">{{ $partner->country }}</span>
                            </td>
                            <td class="p-4 text-gray-700">{{ $partner->contact_person }}</td>
                            <td class="p-4 text-xs">
                                <p class="text-blue-600">{{ $partner->email }}</p>
                                <p class="text-gray-500" dir="ltr">{{ $partner->phone }}</p>
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-orange-100 text-orange-800 text-sm font-bold px-3 py-1 rounded-full">
                                    {{ $partner->candidates_count }} مرشح
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                @if($partner->net_balance > 0)
                                    <span class="block text-green-600 font-extrabold text-sm" title="مبالغ يجب أن نستلمها منهم">
                                        + {{ number_format($partner->net_balance) }} ريال
                                    </span>
                                    <span class="text-[10px] text-green-500">لنا (نطالبهم)</span>
                                @elseif($partner->net_balance < 0)
                                    <span class="block text-red-600 font-extrabold text-sm" title="مبالغ يجب أن ندفعها لهم">
                                        - {{ number_format(abs($partner->net_balance)) }} ريال
                                    </span>
                                    <span class="text-[10px] text-red-500">علينا (يطالبوننا)</span>
                                @else
                                    <span class="block text-gray-400 font-bold text-sm">0 ريال</span>
                                    <span class="text-[10px] text-gray-400">خالص</span>
                                @endif

                                @if($partner->net_balance != 0)
                                    <button wire:click="settlePartnerBalance({{ $partner->id }})" 
                                            onclick="confirm('هل تم استلام/دفع المبالغ فعلياً؟\n\nبموافقتك، سيتم تصفير الرصيد وتحويل كافة المعاملات المعلقة لهذا المكتب إلى (مدفوعة) في الأرشيف.') || event.stopImmediatePropagation()" 
                                            class="mt-2 text-[10px] bg-slate-800 hover:bg-slate-900 text-white font-bold py-1.5 px-3 rounded-md transition shadow-sm w-full flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        تسوية وتصفير
                                    </button>
                                @endif
                            </td>
                            <td class="p-4 flex gap-2 justify-end">
                                <button wire:click="editPartner({{ $partner->id }})" class="text-blue-500 hover:text-blue-700 bg-blue-50 p-2 rounded transition" title="تعديل البيانات وعقد العمولة">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button wire:click="deletePartner({{ $partner->id }})" onclick="confirm('هل أنت متأكد من حذف هذا المكتب؟ لن يتم حذف مرشحيه، ولكن سيتم فك ارتباطهم به.') || event.stopImmediatePropagation()" class="text-red-500 hover:text-red-700 bg-red-50 p-2 rounded transition" title="حذف">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                لا توجد مكاتب شريكة مسجلة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border-t-4 border-orange-500">
                <div class="flex justify-between items-center p-4 lg:p-5 border-b bg-gray-50">
                    <h3 class="text-lg lg:text-xl font-bold text-gray-800">{{ $partnerId ? 'تعديل بيانات المكتب الشريك' : 'تسجيل مكتب شريك جديد' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-red-500 text-2xl transition">&times;</button>
                </div>

                <form wire:submit.prevent="savePartner" class="p-4 lg:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">اسم الوكالة / المكتب *</label>
                            <input type="text" wire:model="agency_name" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-orange-500 focus:outline-none">
                            @error('agency_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">الدولة *</label>
                            <input type="text" wire:model="country" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-orange-500 focus:outline-none">
                            @error('country') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">الشخص المسؤول *</label>
                            <input type="text" wire:model="contact_person" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-orange-500 focus:outline-none">
                            @error('contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">البريد الإلكتروني *</label>
                            <input type="email" wire:model="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-orange-500 focus:outline-none" dir="ltr">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">رقم الهاتف</label>
                            <input type="text" wire:model="phone" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-orange-500 focus:outline-none" dir="ltr">
                            @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-xs font-bold mb-1">تفاصيل اتفاقية العمولة (للتوثيق الداخلي)</label>
                        <textarea wire:model="commission_agreement" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-orange-500 focus:outline-none bg-orange-50" placeholder="مثال: متفق على خصم 2000 ريال من إجمالي الفاتورة لكل مرشح يتم قبوله..."></textarea>
                        @error('commission_agreement') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 border-t pt-4">
                        <button type="button" wire:click="closeModal" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-5 rounded-lg transition">إلغاء</button>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition flex items-center gap-2">
                            حفظ البيانات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>