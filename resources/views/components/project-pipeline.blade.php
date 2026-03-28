<div class="h-full flex flex-col">

    @if (session()->has('message'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition class="absolute top-4 left-1/2 transform -translate-x-1/2 z-[100] bg-green-100 border-2 border-green-500 text-green-800 px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 w-11/12 sm:w-auto justify-between">
            <div class="flex items-center gap-2">
                <span class="text-2xl">🎉</span>
                <span class="font-bold text-sm sm:text-base">{{ session('message') }}</span>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-900 font-bold text-xl mr-4">&times;</button>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition class="absolute top-4 left-1/2 transform -translate-x-1/2 z-[100] bg-red-100 border-2 border-red-500 text-red-800 px-6 py-3 rounded-xl shadow-2xl flex items-center gap-3 w-11/12 sm:w-auto justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-bold text-sm sm:text-base">{{ session('error') }}</span>
            </div>
            <button @click="show = false" class="text-red-600 hover:text-red-900 font-bold text-xl mr-4">&times;</button>
        </div>
    @endif

    <div class="mb-4 bg-white p-3 rounded-lg shadow-sm border-r-4 border-blue-600 flex flex-col md:flex-row gap-3 justify-between items-start md:items-center">
        <div class="flex-shrink-0">
            <h2 class="text-lg lg:text-xl font-bold text-gray-800">{{ $project->title }}</h2>
            <div class="flex items-center gap-2 mt-0.5">
                <span class="text-xs text-gray-500">العميل: <strong class="text-gray-700">{{ $project->client->company_name ?? 'غير محدد' }}</strong></span>
                <span class="text-gray-300">|</span>
                <span class="text-xs text-gray-500">المطلوب: <strong class="text-gray-700">{{ $project->required_count }}</strong></span>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-center w-full md:w-auto">
            @if(count($selectedApplications) > 0)
                <div class="flex gap-1 w-full sm:w-auto bg-blue-50 p-1 rounded border border-blue-100">
                    <button wire:click="openSendBatchModal" class="flex-1 sm:flex-none bg-green-600 hover:bg-green-700 text-white font-bold py-1.5 px-3 rounded shadow-sm flex items-center justify-center gap-1 text-[11px] transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        إرسال ({{ count($selectedApplications) }})
                    </button>
                    <button wire:click="openInterviewModal" class="flex-1 sm:flex-none bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1.5 px-3 rounded shadow-sm flex items-center justify-center gap-1 text-[11px] transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        جدولة
                    </button>
                </div>
            @endif

            <div class="flex w-full sm:w-auto gap-2">
                <div class="inline-flex rounded-md shadow-sm flex-1 sm:flex-none" role="group">
                    <button wire:click="openCandidateModal" class="flex-1 sm:flex-none px-3 py-1.5 text-[11px] font-bold text-blue-700 bg-white border border-gray-200 rounded-r-md hover:bg-blue-50 focus:z-10 focus:ring-1 focus:ring-blue-500 transition flex items-center justify-center gap-1" title="إضافة مرشح يدوياً">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="hidden sm:inline">جديد</span>
                    </button>
                    <button wire:click="openExistingModal" class="flex-1 sm:flex-none px-3 py-1.5 text-[11px] font-bold text-slate-700 bg-white border-t border-b border-gray-200 hover:bg-slate-50 focus:z-10 focus:ring-1 focus:ring-slate-500 transition flex items-center justify-center gap-1" title="استيراد من قاعدة البيانات">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <span class="hidden sm:inline">استيراد</span>
                    </button>
                    <button wire:click="openAiBatchModal" class="flex-1 sm:flex-none px-3 py-1.5 text-[11px] font-bold text-purple-700 bg-white border border-gray-200 rounded-l-md hover:bg-purple-50 focus:z-10 focus:ring-1 focus:ring-purple-500 transition flex items-center justify-center gap-1" title="رفع سير ذاتية بالذكاء الاصطناعي">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        AI CVs
                    </button>
                </div>

                <button wire:click="scoreCandidates" class="flex-1 sm:flex-none bg-amber-500 hover:bg-amber-600 text-white font-bold py-1.5 px-3 rounded shadow-sm text-[11px] transition flex items-center justify-center gap-1" wire:loading.attr="disabled" wire:target="scoreCandidates">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span wire:loading.remove wire:target="scoreCandidates">مطابقة ذكية</span>
                    <span wire:loading wire:target="scoreCandidates">جاري التقييم...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="flex-1 flex gap-4 lg:gap-6 overflow-x-auto pb-4 items-start snap-x">
        
        @foreach($statuses as $statusKey => $statusLabel)
            <div class="w-[85vw] sm:w-72 lg:w-80 flex-shrink-0 bg-gray-100 rounded-xl flex flex-col max-h-full snap-center">
                <div class="p-3 border-b border-gray-200 flex justify-between items-center bg-gray-200/50 rounded-t-xl">
                    <h3 class="font-bold text-gray-700 text-sm lg:text-base">{{ $statusLabel }}</h3>
                    <span class="bg-gray-300 text-gray-700 text-xs font-bold px-2 py-1 rounded-full">
                        {{ $project->applications->where('status', $statusKey)->count() }}
                    </span>
                </div>

                <div class="p-2 lg:p-3 overflow-y-auto flex-1 flex flex-col gap-2 lg:gap-3">
                    @forelse($project->applications->where('status', $statusKey) as $application)
                        <div class="bg-white p-3 lg:p-4 rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    @if(!$application->is_sent)
                                        <input type="checkbox" wire:model.live="selectedApplications" value="{{ $application->id }}" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300 shadow-sm">
                                    @endif
                                    <h4 class="font-bold text-blue-900 text-sm lg:text-base {{ $application->is_sent ? 'ml-1' : '' }}">{{ $application->candidate->first_name }} {{ $application->candidate->last_name }}</h4>
                                </div>
                                
                                <div class="flex gap-2 items-center">
                                    @if($application->candidate->original_cv_path)
                                        <a href="{{ asset('storage/' . $application->candidate->original_cv_path) }}" target="_blank" 
                                        class="text-xs text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded flex items-center gap-1 transition shadow-sm" title="السيرة الذاتية الأصلية">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        </a>
                                    @endif
                                    
                                    <button wire:click="removeCandidate({{ $application->id }})" 
                                            onclick="confirm('هل أنت متأكد من إزالة هذا المرشح من المشروع؟\n\n(ملاحظة: سيتم إزالته من هذا المشروع فقط وسيبقى محفوظاً في قاعدة بيانات النظام)') || event.stopImmediatePropagation()"
                                            class="text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 px-2 py-1 rounded transition shadow-sm" title="إزالة من المشروع">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>

                            @if($application->match_score !== null)
                                <div x-data="{ open: false }" class="mb-3">
                                    <div @click="open = !open" class="flex items-center gap-1 text-[10px] font-bold px-2 py-1.5 rounded w-max cursor-pointer transition-all border shadow-sm select-none
                                        {{ $application->match_score >= 80 ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : ($application->match_score >= 50 ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100' : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100') }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        مطابقة: {{ $application->match_score }}%
                                        
                                        <svg class="w-3 h-3 transition-transform duration-300 ml-1 opacity-70" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                    
                                    <div x-show="open" 
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-2"
                                        style="display: none;" 
                                        class="p-2 mt-1.5 text-[10px] text-gray-700 bg-white rounded-lg border border-gray-200 shadow-inner leading-relaxed">
                                        <strong class="text-gray-900 block mb-1">💡 تحليل الذكاء الاصطناعي:</strong> 
                                        {{ $application->match_reason }}
                                    </div>
                                </div>
                            @endif

                            @if($application->is_sent)
                                <div class="mb-2">
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded border border-green-200">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        أُرسل للعميل
                                    </span>
                                </div>
                            @endif
                            
                            <p class="text-[11px] lg:text-xs text-gray-500 mb-3">{{ $application->candidate->profession }} - {{ $application->candidate->nationality }}</p>
                            
                            @if($application->interview_date)
                                <div class="mb-3 bg-indigo-50 border border-indigo-100 rounded-lg p-2 text-[10px] lg:text-xs shadow-sm relative group">
                                    
                                    <button wire:click="editInterview({{ $application->id }})" class="absolute top-2 left-2 text-indigo-400 hover:text-indigo-700 bg-white hover:bg-indigo-100 p-1.5 rounded shadow-sm opacity-0 group-hover:opacity-100 transition duration-200" title="تعديل أو تأجيل الموعد">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>

                                    <div class="flex items-center gap-1 text-indigo-800 font-bold mb-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        موعد المقابلة:
                                    </div>
                                    <p class="text-indigo-600 pl-4 font-semibold">
                                        {{ \Carbon\Carbon::parse($application->interview_date)->format('Y-m-d') }} 
                                        في تمام {{ \Carbon\Carbon::parse($application->interview_time)->format('h:i A') }}
                                    </p>
                                    @if($application->interview_link)
                                        <a href="{{ str_starts_with($application->interview_link, 'http') ? $application->interview_link : 'https://' . $application->interview_link }}" target="_blank" class="block mt-1 pl-4 text-blue-600 hover:text-blue-800 underline truncate w-11/12">
                                            رابط / تفاصيل المقابلة
                                        </a>
                                    @endif
                                </div>
                            @endif
                            <div class="mb-3 flex flex-wrap gap-1">
                                @if($application->candidate->partner_id)
                                    <span class="inline-flex items-center gap-1 bg-orange-50 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded border border-orange-200" title="هذا المرشح يتبع لمكتب خارجي">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        {{ $application->candidate->partner->agency_name ?? 'مكتب شريك' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200">
                                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        مباشر (Hirehub)
                                    </span>
                                @endif
                            </div>

                            @if($application->client_feedback)
                                <div class="mb-3 p-2 rounded-lg text-[10px] lg:text-xs border shadow-sm relative overflow-hidden {{ $application->status == 'rejected' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}">
                                    <div class="absolute right-0 top-0 bottom-0 w-1 {{ $application->status == 'rejected' ? 'bg-red-500' : 'bg-emerald-500' }}"></div>
                                    
                                    <div class="flex items-center gap-1 font-bold mb-1 mr-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                        رد العميل:
                                    </div>
                                    <p class="font-semibold mr-4">{{ $application->client_feedback }}</p>
                                    <p class="text-[9px] text-gray-500 mt-1 mr-4">آخر تحديث: {{ $application->updated_at->diffForHumans() }}</p>
                                </div>
                            @endif
                            
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <select wire:change="changeStatus({{ $application->id }}, $event.target.value)" 
                                        class="w-full text-xs bg-gray-50 border border-gray-200 text-gray-700 rounded p-1 focus:outline-none focus:border-blue-400">
                                    <option value="" disabled>نقل إلى...</option>
                                    @foreach($statuses as $key => $label)
                                        <option value="{{ $key }}" {{ $statusKey === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @empty
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center text-gray-400 text-xs lg:text-sm">
                            لا يوجد مرشحين
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach

    </div>

    @if($showCandidateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 p-4">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-4 lg:p-6 overflow-y-auto max-h-[90vh]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg lg:text-xl font-bold">إضافة مرشح جديد ورفع السيرة الذاتية</h3>
                    <button wire:click="closeCandidateModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <form wire:submit.prevent="saveCandidate">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-xs lg:text-sm font-bold mb-1">الاسم الأول *</label>
                            <input type="text" wire:model="first_name" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs lg:text-sm font-bold mb-1">اسم العائلة *</label>
                            <input type="text" wire:model="last_name" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-xs lg:text-sm font-bold mb-1">البريد الإلكتروني (اختياري)</label>
                        <input type="email" wire:model="email" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500" dir="ltr">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-xs lg:text-sm font-bold mb-1">الجنسية *</label>
                            <input type="text" wire:model="nationality" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            @error('nationality') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs lg:text-sm font-bold mb-1">المهنة / التخصص *</label>
                            <input type="text" wire:model="profession" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            @error('profession') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-xs lg:text-sm font-bold mb-1">سنوات الخبرة *</label>
                            <input type="number" wire:model="experience_years" min="0" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500">
                            @error('experience_years') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs lg:text-sm font-bold mb-1">المكتب المورد (اختياري)</label>
                            <select wire:model="partner_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-blue-500 bg-gray-50">
                                <option value="">-- مرشح مباشر (Hirehub) --</option>
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}">{{ $partner->agency_name }} ({{ $partner->country }})</option>
                                @endforeach
                            </select>
                            @error('partner_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <label class="block text-blue-900 text-xs lg:text-sm font-bold mb-2">إرفاق السيرة الذاتية (CV) *</label>
                        <input type="file" wire:model="cv_file" accept=".pdf,.doc,.docx" class="w-full text-xs lg:text-sm text-gray-500 file:mr-2 file:py-1 file:px-2 lg:file:mr-4 lg:file:py-2 lg:file:px-4 file:rounded file:border-0 file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                        
                        <div wire:loading wire:target="cv_file" class="text-blue-500 text-xs mt-2 font-semibold">
                            جاري رفع الملف...
                        </div>
                        @error('cv_file') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeCandidateModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-sm lg:text-base">إلغاء</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base" wire:loading.attr="disabled">
                            حفظ المرشح
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showSendBatchModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm p-2 lg:p-4">
            <div class="bg-white rounded-lg shadow-2xl w-full max-w-6xl h-[95vh] lg:h-[90vh] flex flex-col overflow-hidden">
                
                <div class="flex justify-between items-center p-3 lg:p-4 border-b bg-gray-50">
                    <h3 class="text-base lg:text-xl font-bold text-gray-800">مراجعة وإرسال السير الذاتية</h3>
                    <button wire:click="closeSendBatchModal" class="text-gray-400 hover:text-red-600 text-2xl lg:text-3xl transition">&times;</button>
                </div>

                <div class="flex flex-col lg:flex-row flex-1 overflow-hidden">
                    
                    <div class="w-full lg:w-1/3 border-b lg:border-b-0 lg:border-l border-gray-200 bg-white flex flex-col h-1/2 lg:h-full overflow-hidden">
                        <div class="p-2 lg:p-4 bg-blue-50 border-b border-blue-100 hidden sm:block">
                            <p class="text-xs lg:text-sm text-blue-800 font-semibold">قائمة المرشحين للارسال ({{ count($selectedApplications) }}):</p>
                            <p class="text-[10px] lg:text-xs text-blue-600">اضغط على الاسم للمراجعة.</p>
                        </div>

                        <ul class="flex flex-row lg:flex-col overflow-x-auto lg:overflow-y-auto border-b lg:border-b-0 bg-gray-50 lg:bg-white flex-shrink-0 lg:flex-1">
                            @foreach($batchApplicationsData as $app)
                                <li wire:click="setPreview({{ $app->id }})" 
                                    class="cursor-pointer p-2 lg:p-4 border-l lg:border-l-4 lg:border-b hover:bg-gray-100 transition min-w-[140px] lg:min-w-0
                                    {{ $previewApplicationId == $app->id ? 'bg-blue-100 border-blue-600' : 'border-transparent lg:border-gray-100' }}">
                                    
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-gray-800 text-xs lg:text-base truncate">{{ $app->candidate->first_name }}</p>
                                            <p class="text-[10px] lg:text-xs text-gray-500 truncate">{{ $app->candidate->profession }}</p>
                                        </div>
                                        @if($app->candidate->redacted_cv_path)
                                            <span class="text-green-500 hidden lg:block"><svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                                        @else
                                            <span class="text-red-500 text-[10px] lg:text-xs font-bold block lg:hidden">!</span>
                                            <span class="text-red-500 text-xs font-bold hidden lg:block">معالجة..</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="p-3 lg:p-4 bg-gray-50 border-t flex-1 lg:flex-none overflow-y-auto">
                            <div class="mb-3">
                                <label class="block text-gray-700 text-[10px] lg:text-xs font-bold mb-1">إرسال إلى:</label>
                                <p class="text-xs lg:text-sm font-semibold text-gray-800 bg-white p-1 lg:p-2 border rounded truncate">{{ $project->client->contact_email }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="block text-gray-700 text-[10px] lg:text-xs font-bold mb-1">نسخة إلى (CC):</label>
                                <input type="text" wire:model="ccEmails" placeholder="الإيميلات" class="w-full text-xs lg:text-sm border rounded px-2 py-1 lg:py-2 focus:outline-none focus:border-blue-500" dir="ltr">
                            </div>

                            <button wire:click="sendBatchToClient" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 lg:py-3 px-4 rounded shadow transition flex justify-center items-center gap-2 text-sm lg:text-base" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="sendBatchToClient">تأكيد وإرسال</span>
                                <span wire:loading wire:target="sendBatchToClient">جاري الإرسال...</span>
                            </button>
                        </div>
                    </div>

                    <div class="w-full lg:w-2/3 bg-gray-200 relative flex flex-col items-center justify-center border-r h-1/2 lg:h-full">
                        @php
                            $previewApp = $batchApplicationsData->firstWhere('id', $previewApplicationId);
                        @endphp

                        @if($previewApp && $previewApp->candidate->redacted_cv_path)
                            <div class="w-full flex-1 bg-white relative">
                                <iframe src="{{ asset('storage/' . $previewApp->candidate->redacted_cv_path) }}#toolbar=0" class="w-full h-full border-0 absolute inset-0"></iframe>
                            </div>

                            <div class="w-full bg-white border-t p-2 lg:p-3 shadow-inner flex flex-col gap-2 z-10 overflow-y-auto max-h-32 lg:max-h-none">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                                    <span class="text-[10px] lg:text-sm font-bold text-gray-700 hidden sm:inline">هل الطمس الآلي غير دقيق؟</span>
                                    <div class="flex gap-1 lg:gap-2 w-full sm:w-auto">
                                        <a href="{{ asset('storage/' . $previewApp->candidate->original_cv_path) }}" target="_blank" class="flex-1 text-center text-[10px] lg:text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-2 lg:px-3 rounded transition">
                                            تحميل الأصل
                                        </a>
                                        <button wire:click="toggleManualUpload" class="flex-1 text-[10px] lg:text-xs bg-orange-100 text-orange-700 hover:bg-orange-200 font-bold py-1 px-2 lg:px-3 rounded transition">
                                            استبدال برفع يدوي
                                        </button>
                                    </div>
                                </div>

                                @if($isUploadingManual)
                                    <div class="mt-1 p-2 bg-orange-50 border border-orange-200 rounded flex flex-col sm:flex-row items-stretch sm:items-end gap-2 transition-all">
                                        <div class="flex-1">
                                            <input type="file" wire:model="manual_redacted_cv" accept=".pdf" class="w-full text-[10px] lg:text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:font-semibold file:bg-orange-600 file:text-white hover:file:bg-orange-700">
                                        </div>
                                        <button wire:click="saveManualRedaction" class="bg-orange-600 hover:bg-orange-700 text-white text-[10px] lg:text-xs font-bold py-1 lg:py-2 px-3 rounded" wire:loading.attr="disabled" wire:target="manual_redacted_cv, saveManualRedaction">
                                            <span wire:loading.remove wire:target="saveManualRedaction">اعتماد واستبدال</span>
                                            <span wire:loading wire:target="saveManualRedaction">حفظ...</span>
                                        </button>
                                    </div>
                                    @error('manual_redacted_cv') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                                @endif
                            </div>

                        @elseif($previewApp && !$previewApp->candidate->redacted_cv_path)
                            <div class="text-center text-gray-500 my-auto p-4">
                                <svg class="animate-spin h-6 w-6 lg:h-10 lg:w-10 mx-auto mb-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <p class="text-xs lg:text-base">جاري الطمس والمعالجة...</p>
                            </div>
                        @else
                            <div class="text-gray-400 text-center my-auto p-4 text-xs lg:text-base">
                                <p>اختر مرشحاً من القائمة</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @endif

    @if($showAiBatchModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm p-4">
            <div class="bg-white rounded-lg shadow-2xl w-full max-w-xl p-4 lg:p-6 border-t-4 border-purple-600">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base lg:text-xl font-bold flex items-center gap-2 text-purple-800">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        قراءة السير بالذكاء الاصطناعي
                    </h3>
                    <button wire:click="closeAiBatchModal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <div class="mb-4 text-xs lg:text-sm text-gray-600 bg-purple-50 p-2 lg:p-3 rounded border border-purple-100">
                    حدد مجموعة من السير الذاتية. سيقوم <strong>Gemini AI</strong> بقراءتها وتعبئة بيانات المرشحين تلقائياً!
                </div>

                <form wire:submit.prevent="processAiBatch">
                    <div class="mb-4 lg:mb-6 p-3 bg-purple-50 border border-purple-100 rounded-lg">
                        <label class="block text-purple-800 text-xs lg:text-sm font-bold mb-2">مصدر هذه السير الذاتية (المكتب الشريك - اختياري):</label>
                        <select wire:model="batch_partner_id" class="w-full border border-purple-200 rounded px-3 py-2 text-sm focus:outline-none focus:border-purple-500 bg-white">
                            <option value="">-- سير ذاتية مباشرة لـ Hirehub --</option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">{{ $partner->agency_name }}</option>
                            @endforeach
                        </select>
                        <div class="mt-3 flex flex-col sm:flex-row gap-2 items-center bg-white p-2 rounded border border-purple-100">
                            <button type="button" wire:click="generateMagicLink" class="w-full sm:w-auto bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold py-2 px-3 rounded transition">
                                🔗 توليد رابط سري للمكتب
                            </button>
                            
                            @if($magicLink)
                                <div class="flex-1 w-full flex relative">
                                    <input type="text" readonly value="{{ $magicLink }}" id="magicLinkInput" class="w-full text-[10px] lg:text-xs border border-gray-300 p-2 rounded bg-gray-100 text-gray-600 focus:outline-none pr-16" dir="ltr">
                                    <button type="button" onclick="let copyText = document.getElementById('magicLinkInput'); copyText.select(); document.execCommand('copy'); alert('تم نسخ الرابط! يمكنك إرساله للمكتب الآن.');" class="absolute right-1 top-1 bg-green-100 text-green-700 hover:bg-green-200 text-[10px] font-bold py-1 px-2 rounded">
                                        نسخ الرابط
                                    </button>
                                </div>
                            @endif
                        </div>
                        <p class="text-[10px] text-purple-600 mt-1">إذا اخترت مكتباً، سيتم ربط كل الملفات المرفوعة أدناه بهذا المكتب تلقائياً لضمان حقوقهم المالية.</p>
                    </div>

                    
                    <div class="mb-4 lg:mb-6">
                        <input type="file" wire:model="batch_cv_files" multiple accept=".pdf,.jpg,.jpeg,.png" class="w-full text-[10px] lg:text-sm text-gray-500 file:mr-2 file:py-2 file:px-2 lg:file:mr-4 lg:file:py-3 lg:file:px-4 file:rounded file:border-0 file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 border border-gray-300 rounded p-1 lg:p-2">
                        
                        <div wire:loading wire:target="batch_cv_files" class="text-blue-500 text-[10px] lg:text-xs mt-2 font-semibold flex items-center gap-1">
                            <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            جاري الرفع للسيرفر المؤقت، يرجى الانتظار...
                        </div>
                        
                        @error('batch_cv_files') <span class="text-red-500 text-xs block mt-1 font-bold">{{ $message }}</span> @enderror
                        @error('batch_cv_files.*') <span class="text-red-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-3 lg:px-4 rounded flex items-center gap-1 lg:gap-2 text-xs lg:text-base disabled:opacity-50 disabled:cursor-not-allowed" 
                        wire:loading.attr="disabled" 
                        wire:target="processAiBatch, batch_cv_files"> <span wire:loading.remove wire:target="processAiBatch">ابدأ التحليل</span>
                        <span wire:loading wire:target="processAiBatch">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            جاري تحليل الـ AI...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    @endif


    @if($showFinancialModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border-t-4 border-green-500">
                
                <div class="bg-green-50 p-4 border-b border-green-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-green-800 flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            إغلاق الشاغر والتسوية المالية
                        </h3>
                        <p class="text-xs text-green-600 mt-1">يُرجى إدخال تفاصيل الرسوم لحساب عمولتك تلقائياً.</p>
                    </div>
                    <button wire:click="closeFinancialModal" class="text-gray-400 hover:text-red-500 text-3xl transition">&times;</button>
                </div>

                <form wire:submit.prevent="saveSettlement" class="p-6">
                    
                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-bold mb-2">إجمالي رسوم التوظيف (العميل أو المرشح) *</label>
                        <div class="relative">
                            <input type="number" step="0.01" wire:model.live.debounce.500ms="total_fee" class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-green-500 font-bold text-lg" placeholder="مثال: 10000">
                            <span class="absolute left-4 top-3 text-gray-400 font-bold">ريال</span>
                        </div>
                        @error('total_fee') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    @if($has_partner)
                        <div class="mb-5 bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-gray-800 text-sm font-bold mb-3">الجهة التي استلمت الرسوم (المُحصِّل):</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model.live="fee_collector" value="hirehub" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-semibold">نحن (Hirehub)</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model.live="fee_collector" value="partner" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
                                    <span class="text-sm font-semibold">المكتب الشريك ({{ $partner_name }})</span>
                                </label>
                            </div>
                            
                            <div class="mt-3 text-[11px] font-bold p-2 rounded {{ $fee_collector == 'hirehub' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                @if($fee_collector == 'hirehub')
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    المال بحوزتنا. نحن مدينون للمكتب الشريك بمبلغ عمولتهم.
                                @else
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    المال بحوزة المكتب الشريك. هم مدينون لنا بصافي الأرباح.
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($has_partner)
                        <div class="mb-5 p-4 bg-orange-50 border border-orange-200 rounded-lg">
                            <label class="block text-orange-800 text-sm font-bold mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                خصم حصة المكتب الشريك: ({{ $partner_name }})
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" wire:model.live.debounce.500ms="partner_fee" class="w-full border border-orange-300 rounded px-3 py-2 focus:outline-none focus:border-orange-500" placeholder="أدخل مبلغ المكتب الشريك هنا...">
                            </div>
                            <p class="text-[10px] text-orange-600 mt-1">سيتم خصم هذا المبلغ من الإجمالي قبل حساب أرباح الوكالة.</p>
                            @error('partner_fee') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <div class="mb-5 p-3 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-500 flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            هذا المرشح مباشر لـ Hirehub. الأرباح الصافية ستكون بنسبة 100%.
                        </div>
                    @endif

                    <div class="bg-slate-900 rounded-lg p-4 text-white mb-6 shadow-inner">
                        <div class="flex justify-between items-center border-b border-slate-700 pb-2 mb-2">
                            <span class="text-slate-400 text-sm">صافي أرباح الوكالة:</span>
                            <span class="font-bold text-lg">{{ number_format($net_profit, 2) }} <span class="text-xs text-slate-400">ريال</span></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-green-400 text-sm font-bold flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                عمولة الموظف المستحقة ({{ auth()->user()->commission_rate ?? 5 }}%):
                            </span>
                            <span class="font-bold text-2xl text-green-400">{{ number_format($my_commission, 2) }} <span class="text-xs">ريال</span></span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeFinancialModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition">إلغاء</button>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow-md transition flex items-center gap-2" {{ $total_fee <= 0 ? 'disabled' : '' }}>
                            تأكيد التوظيف وحفظ العمولة
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif


    @if($showExistingModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col overflow-hidden border-t-4 border-blue-600">
                
                <div class="flex justify-between items-center p-4 border-b bg-slate-50">
                    <div>
                        <h3 class="text-lg lg:text-xl font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                            إعادة تدوير المواهب (Talent Pool)
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">استورد أفضل المرشحين من مشاريعك السابقة بضغطة زر.</p>
                    </div>
                    <button wire:click="closeExistingModal" class="text-gray-400 hover:text-red-600 text-3xl transition">&times;</button>
                </div>

                <div class="p-4 bg-white border-b shadow-sm z-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        
                        <div class="relative">
                            <label class="block text-[10px] text-gray-500 font-bold mb-1">بحث حر:</label>
                            <input type="text" wire:model.live.debounce.300ms="searchCandidate" placeholder="الاسم، المهنة، الجنسية..." class="w-full pl-4 pr-10 py-2 border-2 border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm transition">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>

                        <div>
                            <label class="block text-[10px] text-gray-500 font-bold mb-1">استخراج من مشروع سابق:</label>
                            <select wire:model.live="filterProjectId" class="w-full px-3 py-2 border-2 border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm text-gray-700 font-semibold transition bg-gray-50 cursor-pointer">
                                <option value="">🌐 جميع المشاريع في النظام</option>
                                @foreach($otherProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }} ({{ $p->client->company_name ?? 'بدون عميل' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] text-gray-500 font-bold mb-1">حالة المرشح في ذلك المشروع:</label>
                            <select wire:model.live="filterStatus" class="w-full px-3 py-2 border-2 border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 text-sm text-gray-700 font-semibold transition bg-gray-50 cursor-pointer">
                                <option value="">🔄 جميع الحالات</option>
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                <div class="flex-1 overflow-y-auto bg-slate-100 p-4">
                    @if(count($availableCandidates) > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                            @foreach($availableCandidates as $cand)
                                <label class="flex items-start p-3 bg-white border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition shadow-sm">
                                    <input type="checkbox" wire:model.live="selectedExisting" value="{{ $cand->id }}" class="mt-1 w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <div class="mr-3 flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-bold text-gray-800 text-sm lg:text-base">{{ $cand->first_name }} {{ $cand->last_name }}</p>
                                                <p class="text-[11px] lg:text-xs text-blue-600 font-semibold mt-0.5">{{ $cand->profession }}</p>
                                            </div>
                                            <span class="text-[10px] font-bold text-slate-500 bg-slate-200 px-2 py-1 rounded">{{ $cand->nationality }}</span>
                                        </div>
                                        <div class="mt-2 flex items-center justify-between border-t pt-2">
                                            <span class="text-[10px] text-gray-500 flex items-center gap-1">
                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                خبرة: <strong class="text-gray-700">{{ $cand->experience_years }} سنوات</strong>
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-gray-400">
                            <div class="bg-gray-200 p-4 rounded-full mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </div>
                            <p class="font-bold text-gray-600">لم نجد مرشحين يطابقون فلاتر البحث الحالية.</p>
                            <p class="text-xs mt-1">جرب تغيير حالة المرشح، أو اختر مشروعاً مختلفاً.</p>
                        </div>
                    @endif
                </div>

                <div class="p-4 border-t bg-white flex justify-between items-center shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                    <span class="text-sm font-bold {{ count($selectedExisting) > 0 ? 'text-blue-600' : 'text-gray-400' }}">
                        تم تحديد: <span class="text-lg">{{ count($selectedExisting) }}</span> مرشح
                    </span>
                    <div class="flex gap-2">
                        <button wire:click="closeExistingModal" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-5 rounded-lg transition">إلغاء</button>
                        <button wire:click="addExistingToProject" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2" {{ empty($selectedExisting) ? 'disabled' : '' }}>
                            استيراد للمشروع الحالي
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif


    @if($showInterviewModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 backdrop-blur-sm p-4">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden border-t-4 border-indigo-600">
                <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                    <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        جدولة مقابلة للمرشحين ({{ count($selectedApplications) }})
                    </h3>
                    <button wire:click="closeInterviewModal" class="text-gray-400 hover:text-red-600 text-2xl transition">&times;</button>
                </div>

                <form wire:submit.prevent="scheduleInterviews" class="p-5">
                    <div class="mb-4 text-xs text-gray-500 bg-indigo-50 p-2 rounded">
                        سيتم نقل المرشحين المحددين تلقائياً إلى حالة "مرحلة المقابلات" بعد الحفظ.
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">تاريخ المقابلة *</label>
                            <input type="date" wire:model="interview_date" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                            @error('interview_date') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1">وقت المقابلة *</label>
                            <input type="time" wire:model="interview_time" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500">
                            @error('interview_time') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-xs font-bold mb-1">رابط المقابلة (Zoom/Teams) أو المكان</label>
                        <input type="text" wire:model="interview_link" placeholder="مثال: https://zoom.us/j/123456789" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-indigo-500" dir="ltr">
                        @error('interview_link') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6 border-t border-gray-100 pt-4 space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="notify_client" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm font-bold text-gray-700">إرسال تفاصيل الجدولة إلى إيميل العميل ({{ $project->client->contact_email ?? 'غير محدد' }})</span>
                        </label>
                        
                        <label class="flex items-center gap-2 cursor-pointer mt-2">
                            <input type="checkbox" wire:model="notify_candidates" class="w-5 h-5 text-green-600 rounded border-gray-300 focus:ring-green-500">
                            <span class="text-sm font-bold text-gray-700">إرسال دعوة المقابلة والرابط إلى إيميل المرشحين (إن وجد)</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeInterviewModal" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg transition">إلغاء</button>
                        
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-md flex items-center gap-2 disabled:opacity-50" wire:loading.attr="disabled" wire:target="scheduleInterviews">
                            <span wire:loading.remove wire:target="scheduleInterviews">حفظ الجدولة</span>
                            <span wire:loading wire:target="scheduleInterviews">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                جاري الحفظ والإرسال...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
