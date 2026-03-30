<div class="h-full flex flex-col bg-slate-50/50 p-4 lg:p-8">
    
    <div class="mb-8">
        <h2 class="text-2xl lg:text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            لوحة التقارير
        </h2>
        <p class="text-slate-500 mt-2 text-sm lg:text-base">قم بتوليد تقارير احترافية لمشاريعك بصيغة PDF لمشاركتها مع العملاء والمستشفيات.</p>
    </div>

    <div class="bg-white p-6 lg:p-10 rounded-2xl shadow-sm border border-slate-200 max-w-3xl">
        <h3 class="text-lg font-bold text-slate-800 mb-5 border-b border-slate-100 pb-3">تقرير حالة المشروع (Project Status Report)</h3>
        
        <form wire:submit.prevent="downloadReport" class="flex flex-col gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">اختر المشروع المستهدف:</label>
                <select wire:model="selectedProjectId" class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-slate-50 text-slate-700 font-medium cursor-pointer">
                    <option value="">-- اضغط لاختيار مشروع --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">
                            {{ $project->title }} @if($project->client) (العميل: {{ $project->client->company_name }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('selectedProjectId') <span class="text-rose-500 text-xs mt-2 block font-semibold">{{ $message }}</span> @enderror
            </div>

            <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-xs text-blue-800 leading-relaxed">
                    سيتم استخراج التقرير باللغة الإنجليزية. سيحتوي التقرير على تفاصيل المشروع، قائمة المرشحين، حالتهم الحالية (مقبول، مرفوض، قيد المقابلة)، وملاحظات المستشفى. سيتم إخفاء أي بيانات مالية أو معلومات عن المكاتب الموردة.
                </p>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-colors flex items-center gap-3" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="downloadReport">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </span>
                    <span wire:loading.remove wire:target="downloadReport">توليد وتحميل PDF</span>
                    <span wire:loading wire:target="downloadReport" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        جاري التجهيز...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>