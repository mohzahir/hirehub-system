<div class="h-full flex flex-col bg-slate-50/50 p-4 lg:p-8 overflow-y-auto">
    
    <div class="mb-8">
        <h2 class="text-2xl lg:text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            لوحة التقارير
        </h2>
        <p class="text-slate-500 mt-2 text-sm lg:text-base">قم بتوليد تقارير احترافية لمشاريعك وعملائك بصيغة PDF لمشاركتها مع المستشفيات.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-7xl">
        
        <div class="bg-white p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-4">
                <div class="bg-blue-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">تقرير حالة مشروع (مفرد)</h3>
            </div>
            
            <form wire:submit.prevent="downloadProjectReport" class="flex flex-col gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اختر المشروع المستهدف:</label>
                    <select wire:model="selectedProjectId" class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-slate-50 text-slate-700 font-medium cursor-pointer">
                        <option value="">-- اضغط لاختيار مشروع --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">
                                {{ $project->title }} @if($project->client) ({{ $project->client->company_name }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('selectedProjectId') <span class="text-rose-500 text-xs mt-2 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="bg-slate-50 p-4 rounded-xl text-xs text-slate-600 leading-relaxed border border-slate-200">
                    يعرض هذا التقرير تفاصيل وحالة المرشحين لمشروع واحد (شاغر وظيفي واحد) محدد.
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors flex items-center justify-center gap-3" wire:loading.attr="disabled" wire:target="downloadProjectReport">
                        <span wire:loading.remove wire:target="downloadProjectReport">توليد PDF</span>
                        <span wire:loading wire:target="downloadProjectReport" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            جاري التجهيز...
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 lg:p-8 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3 mb-5 border-b border-slate-100 pb-4">
                <div class="bg-indigo-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-5 11h3m-3 4.5V19"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">تقرير العميل الشامل (مستشفى)</h3>
            </div>
            
            <form wire:submit.prevent="downloadClientReport" class="flex flex-col gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">اختر العميل (المستشفى):</label>
                    <select wire:model="selectedClientId" class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all bg-slate-50 text-slate-700 font-medium cursor-pointer">
                        <option value="">-- اضغط لاختيار مستشفى --</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->company_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('selectedClientId') <span class="text-rose-500 text-xs mt-2 block font-semibold">{{ $message }}</span> @enderror
                </div>

                <div class="bg-indigo-50 p-4 rounded-xl text-xs text-indigo-800 leading-relaxed border border-indigo-100">
                    يعرض هذا التقرير الشامل جميع المشاريع (الشواغر) المفتوحة والمغلقة الخاصة بهذا العميل، مع قائمة المرشحين أسفل كل مشروع.
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors flex items-center justify-center gap-3" wire:loading.attr="disabled" wire:target="downloadClientReport">
                        <span wire:loading.remove wire:target="downloadClientReport">توليد PDF شامل</span>
                        <span wire:loading wire:target="downloadClientReport" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            جاري التجهيز...
                        </span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>