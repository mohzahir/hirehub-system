<div class="p-4 sm:p-6 space-y-6">

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex items-center gap-2 font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-red-500 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-bold mb-1">الديون المتبقية (قيد التحصيل)</p>
                <h3 class="text-3xl font-black text-red-600">{{ number_format($totalExpected) }} <span class="text-sm">ريال</span></h3>
            </div>
            <div class="bg-red-50 p-3 rounded-full text-red-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-r-4 border-green-500 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-500 font-bold mb-1">إجمالي الأرباح المحصلة (بالخزينة)</p>
                <h3 class="text-3xl font-black text-green-600">{{ number_format($totalCollected) }} <span class="text-sm">ريال</span></h3>
            </div>
            <div class="bg-green-50 p-3 rounded-full text-green-500">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
        <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                فواتير ومطالبات قيد الانتظار
            </h3>
            <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded-full">{{ count($pendingCollections) }} مطالبة</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-white text-gray-500 text-xs uppercase border-b">
                        <th class="p-4 font-bold">المرشح / المشروع</th>
                        <th class="p-4 font-bold">نوع المرشح (المصدر)</th>
                        <th class="p-4 font-bold text-center">المبلغ المطلوب مننا تحصيله</th>
                        <th class="p-4 font-bold text-center">حالة الدفع (المدفوع / المتبقي)</th>
                        <th class="p-4 font-bold text-left">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pendingCollections as $placement)
                        @php
                            $target = $placement->fee_collector == 'hirehub' ? $placement->total_agency_fee : $placement->net_profit;
                            $paid = $placement->amount_paid;
                            $remaining = $target - $paid;
                            $progress = $target > 0 ? ($paid / $target) * 100 : 0;
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4">
                                <p class="font-bold text-gray-800 text-sm">{{ $placement->application->candidate->first_name }} {{ $placement->application->candidate->last_name }}</p>
                                <p class="text-xs text-blue-600 mt-1">المشروع: {{ $placement->application->project->title }}</p>
                            </td>
                            
                            <td class="p-4">
                                @if($placement->application->candidate->partner_id)
                                    <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-700 text-xs font-bold px-2 py-1 rounded border border-orange-200">
                                        مكتب: {{ $placement->application->candidate->partner->agency_name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded border border-gray-200">
                                        مباشر (لا يوجد شريك)
                                    </span>
                                @endif
                                <p class="text-[10px] text-gray-500 mt-1">المُحصل: <strong class="{{ $placement->fee_collector == 'hirehub' ? 'text-blue-600' : 'text-orange-600' }}">{{ $placement->fee_collector == 'hirehub' ? 'نحن (المرشح يدفع لنا)' : 'المكتب الشريك يحول لنا' }}</strong></p>
                            </td>

                            <td class="p-4 text-center font-black text-gray-800 text-lg">
                                {{ number_format($target) }} <span class="text-xs text-gray-400">ريال</span>
                            </td>

                            <td class="p-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-1">
                                    <div class="bg-green-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="flex justify-between text-[11px] font-bold mt-1">
                                    <span class="text-green-600">دفع: {{ number_format($paid) }}</span>
                                    <span class="text-red-500">متبقي: {{ number_format($remaining) }}</span>
                                </div>
                            </td>

                            <td class="p-4">
                                <div class="flex flex-col gap-2 justify-end items-end">
                                    <button wire:click="openPaymentModal({{ $placement->id }})" class="w-32 bg-slate-800 hover:bg-slate-900 text-white font-bold py-1.5 px-3 rounded shadow-sm transition flex items-center justify-center gap-1 text-[11px]">
                                        <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        تسجيل دفعة
                                    </button>
                                    
                                    <a href="{{ route('invoice.show', $placement->id) }}" target="_blank" class="w-32 bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-600 hover:text-white font-bold py-1.5 px-3 rounded shadow-sm transition flex items-center justify-center gap-1 text-[11px]">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        عرض الفاتورة
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-400">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                <p class="text-lg font-bold text-gray-600">الخزينة مطابقة ولا توجد مبالغ معلقة!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border-t-4 border-green-500">
                <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        تسجيل استلام مالي (سند قبض)
                    </h3>
                    <button wire:click="closePaymentModal" class="text-gray-400 hover:text-red-600 text-2xl transition">&times;</button>
                </div>

                <form wire:submit.prevent="savePayment" class="p-5">
                    <div class="flex justify-between text-sm mb-4 bg-green-50 p-3 rounded border border-green-100">
                        <div>
                            <p class="text-gray-500">إجمالي المطلوب:</p>
                            <p class="font-bold">{{ number_format($target_amount) }} ريال</p>
                        </div>
                        <div class="text-left">
                            <p class="text-gray-500">المتبقي للتحصيل:</p>
                            <p class="font-bold text-red-600">{{ number_format($remaining_amount) }} ريال</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">المبلغ المستلم الآن *</label>
                        <div class="relative">
                            <input type="number" step="0.01" wire:model="payment_amount" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-green-500 font-bold text-lg" placeholder="أدخل المبلغ...">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">ريال</span>
                        </div>
                        @error('payment_amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 border-t pt-4">
                        <button type="button" wire:click="closePaymentModal" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg transition">إلغاء</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">تأكيد الاستلام</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>