<div class="min-h-screen bg-slate-50 font-sans pb-12">
    
    <div class="bg-blue-900 text-white py-8 px-4 sm:px-6 lg:px-8 shadow-md">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">بوابة مراجعة المرشحين</h1>
                    <p class="text-blue-200 text-sm">مقدمة من: وكالة Hirehub للتوظيف</p>
                </div>
            </div>
            <div class="text-right bg-blue-800 p-3 rounded-lg border border-blue-700">
                <p class="text-xs text-blue-300">المشروع الحالي:</p>
                <h2 class="text-lg font-bold">{{ $project->title }}</h2>
                <p class="text-sm font-semibold text-yellow-400">لصالح: {{ $project->client->company_name ?? 'العميل' }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="bg-green-100 border-r-4 border-green-500 text-green-800 p-4 mb-6 rounded shadow-sm flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold">{{ session('message') }}</span>
            </div>
        @endif

        <h3 class="text-xl font-bold text-gray-800 mb-6 border-b-2 border-blue-500 pb-2 inline-block">⏳ مرشحون بانتظار قراركم ({{ count($pendingApps) }})</h3>
        
        @if(count($pendingApps) > 0)
            <div class="space-y-6 mb-12">
                @foreach($pendingApps as $app)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-lg transition duration-300">
                        
                        <div class="p-6 md:w-5/12 bg-gray-50 flex flex-col justify-center border-b md:border-b-0 md:border-l border-gray-200">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-xl text-gray-800">{{ $app->candidate->first_name }} {{ $app->candidate->last_name }}</h4>
                                <span class="bg-slate-200 text-slate-700 text-xs font-bold px-3 py-1 rounded-full">{{ $app->candidate->nationality }}</span>
                            </div>
                            <p class="text-blue-600 font-bold text-base mb-4">{{ $app->candidate->profession }}</p>
                            
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>سنوات الخبرة: <strong class="text-gray-800">{{ $app->candidate->experience_years }} سنوات</strong></span>
                            </div>
                        </div>
                        
                        <div class="p-6 md:w-4/12 flex flex-col justify-center items-center md:items-start border-b md:border-b-0 md:border-l border-gray-100">
                            <p class="text-xs text-gray-400 font-bold mb-3 uppercase tracking-wider">المرفقات</p>
                            @if($app->candidate->redacted_cv_path)
                                <a href="{{ asset('storage/' . $app->candidate->redacted_cv_path) }}" target="_blank" class="w-full bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 font-bold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    استعراض السيرة الذاتية
                                </a>
                            @else
                                <div class="text-center text-sm text-orange-600 bg-orange-50 p-3 rounded-lg w-full border border-orange-100 font-semibold">
                                    ⏳ جاري المعالجة...
                                </div>
                            @endif
                        </div>

                        <div class="flex flex-row md:flex-col md:w-3/12 bg-white">
                            <button wire:click="acceptCandidate({{ $app->id }})" class="flex-1 py-4 md:py-0 md:h-1/2 text-green-600 hover:bg-green-50 font-bold text-lg transition flex items-center justify-center gap-2 border-l md:border-l-0 md:border-b border-gray-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                قبول للمقابلة
                            </button>
                            <button wire:click="openRejectModal({{ $app->id }})" class="flex-1 py-4 md:py-0 md:h-1/2 text-red-500 hover:bg-red-50 font-bold text-lg transition flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                استبعاد
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white p-12 rounded-xl shadow-sm text-center text-gray-500 mb-12 border border-gray-200">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <p class="text-xl font-bold text-gray-600">لا يوجد مرشحون جدد بانتظار المراجعة حالياً.</p>
                <p class="text-base mt-2">سنقوم بإرسال المزيد من المرشحين قريباً.</p>
            </div>
        @endif

        @if(count($reviewedApps) > 0)
            <h3 class="text-xl font-bold text-gray-400 mb-4 border-b-2 border-gray-300 pb-2 inline-block">✅ مرشحون تمت مراجعتهم سابقاً</h3>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden opacity-80 hover:opacity-100 transition">
                <div class="overflow-x-auto">
                    <table class="w-full text-right min-w-[700px]">
                        <thead class="bg-gray-100 text-gray-600 text-sm">
                            <tr>
                                <th class="p-4 border-b font-bold">المرشح</th>
                                <th class="p-4 border-b font-bold">المهنة</th>
                                <th class="p-4 border-b font-bold text-center">القرار</th>
                                <th class="p-4 border-b font-bold">ملاحظاتك</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-base">
                            @foreach($reviewedApps as $app)
                                <tr>
                                    <td class="p-4 font-bold text-gray-800">{{ $app->candidate->first_name }} {{ $app->candidate->last_name }}</td>
                                    <td class="p-4 text-blue-600 font-semibold">{{ $app->candidate->profession }}</td>
                                    <td class="p-4 text-center">
                                        @if($app->status == 'rejected')
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">مرفوض</span>
                                        @else
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">مقبول للمقابلة</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-500 text-sm truncate max-w-xs" title="{{ $app->client_feedback }}">{{ $app->client_feedback }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>

    @if($showRejectModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 p-4 backdrop-blur-sm">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border-t-4 border-red-600">
                <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-red-700 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        استبعاد المرشح
                    </h3>
                </div>
                <form wire:submit.prevent="confirmRejection" class="p-6">
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">يرجى توضيح سبب استبعاد هذا المرشح، سيساعدنا ذلك كثيراً في توفير مرشحين أفضل وأكثر دقة لاحتياجاتكم في المرات القادمة.</p>
                    <textarea wire:model="rejection_reason" rows="4" placeholder="مثال: الخبرة غير كافية، أو الراتب المتوقع عالي..." class="w-full border-2 border-gray-200 rounded-lg p-3 text-base focus:border-red-500 focus:outline-none"></textarea>
                    @error('rejection_reason') <span class="text-red-500 text-sm mt-1 font-bold">{{ $message }}</span> @enderror
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="$set('showRejectModal', false)" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-5 rounded-lg transition">إلغاء</button>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md">تأكيد الرفض</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>