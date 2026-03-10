<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-xl">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-blue-900 flex justify-center items-center gap-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            Hirehub ATS
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            بوابة الإرسال المباشر للشركاء المعتمدين
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border-t-4 border-blue-600">
            
            <div class="mb-6 pb-6 border-b border-gray-200">
                <p class="text-xs text-gray-500 font-bold mb-1">مرحباً بفريق:</p>
                <h3 class="text-xl font-bold text-orange-600">{{ $partner->agency_name }}</h3>
                <div class="mt-3 bg-blue-50 p-3 rounded text-sm text-blue-800">
                    أنت تقوم الآن برفع السير الذاتية لمشروع: <br>
                    <strong class="text-lg">{{ $project->title }}</strong>
                </div>
            </div>

            @if($isProcessed)
                <div class="text-center py-6">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">شكراً لتعاونكم!</h2>
                    <p class="text-gray-600 mb-4">تمت معالجة السير الذاتية المرسلة إلى Hirehub.</p>
                    
                    <div class="bg-gray-50 p-4 rounded-lg text-sm font-bold flex flex-col gap-2 text-right border border-gray-200">
                        <div class="flex justify-between items-center text-green-600 bg-green-50 p-2 rounded">
                            <span>✔ تم الإرسال بنجاح:</span>
                            <span class="text-lg">{{ $successCount }} مرشح</span>
                        </div>
                        
                        @if($skippedCount > 0) 
                            <div class="flex justify-between items-center text-orange-600 bg-orange-50 p-2 rounded" title="هؤلاء المرشحون قمت بإرسالهم مسبقاً لهذا المشروع">
                                <span>⚠ تم التخطي (مكرر):</span>
                                <span class="text-lg">{{ $skippedCount }} مرشح</span>
                            </div>
                        @endif

                        @if($failedCount > 0) 
                            <div class="flex justify-between items-center text-red-600 bg-red-50 p-2 rounded">
                                <span>✖ تعذر قراءة الملف:</span>
                                <span class="text-lg">{{ $failedCount }} ملف</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <form wire:submit.prevent="processPartnerUploads">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">قم بتحديد السير الذاتية هنا (PDF, DOCX)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-500 transition bg-gray-50 relative">
                            <input type="file" wire:model="batch_cv_files" multiple accept=".pdf,.doc,.docx" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <span class="relative font-bold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">اضغط لاختيار الملفات</span>
                                </div>
                                <p class="text-xs text-gray-500">حتى 20 ملف في الدفعة الواحدة</p>
                            </div>
                        </div>
                        
                        <div wire:loading wire:target="batch_cv_files" class="text-blue-500 text-xs mt-2 font-bold text-center w-full">
                            جاري تهيئة الملفات للرفع...
                        </div>
                        @error('batch_cv_files') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        @error('batch_cv_files.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition disabled:opacity-50" wire:loading.attr="disabled" wire:target="processPartnerUploads, batch_cv_files">
                        <span wire:loading.remove wire:target="processPartnerUploads">إرسال وبدء المعالجة الذكية</span>
                        <span wire:loading wire:target="processPartnerUploads">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            جاري القراءة والمعالجة... يرجى عدم إغلاق الصفحة.
                        </span>
                    </button>
                </form>
            @endif

        </div>
    </div>
</div>