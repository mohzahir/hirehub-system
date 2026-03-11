<div class="min-h-screen bg-[#f4f7f9] font-sans selection:bg-blue-600 selection:text-white pb-0 sm:pb-12">
    
    <div class="absolute top-0 left-0 right-0 h-72 sm:h-96 bg-gradient-to-b from-slate-900 to-slate-800 z-0 overflow-hidden rounded-b-[40px] sm:rounded-none shadow-lg">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-0 sm:px-6 lg:px-8 pt-8 sm:pt-12">
        
        <header class="flex justify-between items-center mb-8 px-6 sm:px-0 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white text-blue-700 rounded-2xl shadow-xl flex items-center justify-center transform -rotate-3 border-b-4 border-blue-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl sm:text-2xl font-black tracking-wide">Hirehub</h1>
                    <p class="text-[10px] text-slate-300 tracking-widest uppercase">للاستشارات والتوظيف</p>
                </div>
            </div>
            
            <div class="hidden sm:flex items-center gap-2 text-sm font-semibold bg-white/10 px-4 py-2 rounded-full backdrop-blur-md border border-white/20">
                <svg class="w-4 h-4 text-green-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                بوابة التقديم الآمنة
            </div>
        </header>

        <div class="bg-white rounded-t-[40px] sm:rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row border-0 sm:border border-slate-100 min-h-[80vh] sm:min-h-0">
            
            <div class="w-full md:w-5/12 bg-slate-50/50 p-6 sm:p-10 lg:p-12 border-b md:border-b-0 md:border-l border-slate-200 flex flex-col relative overflow-hidden">
                
                <svg class="absolute -top-10 -right-10 w-48 h-48 text-slate-100 transform rotate-12 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>

                <div class="mb-8 relative z-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-100 text-green-700 text-xs font-black rounded-full mb-5 border border-green-200 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-ping"></span>
                        توظيف عاجل
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-800 leading-tight mb-3">{{ $project->title }}</h2>
                    
                    <div class="flex items-center gap-2 text-slate-600 text-sm font-bold bg-white px-4 py-2.5 rounded-xl border border-slate-200 shadow-sm w-max">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-5 11h3m-3 4.5V19"></path></svg>
                        لصالح إحدى الجهات الكبرى
                    </div>
                </div>

                @if($project->description)
                    <div class="mb-8 flex-1 relative z-10">
                        <h4 class="font-black text-slate-800 mb-4 text-sm sm:text-base flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500 bg-blue-100 rounded-full p-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            المهام والمسؤوليات:
                        </h4>
                        
                        <div class="text-sm sm:text-base leading-loose text-slate-700 bg-white p-5 sm:p-6 rounded-2xl border border-slate-100 shadow-sm whitespace-pre-line font-medium">
                            {{ $project->description }}
                        </div>
                    </div>
                @endif
                
                <div class="mt-auto bg-blue-50/80 border border-blue-100 p-5 rounded-2xl text-center shadow-inner relative z-10">
                    <div class="flex justify-center mb-3">
                        <div class="bg-blue-600 text-white p-2.5 rounded-xl shadow-md transform -rotate-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-blue-900 text-xs sm:text-sm font-bold leading-relaxed">
                        يتم تحديد الراتب الإجمالي والمزايا وبدلات السكن بناءً على الخبرة والتقييم بعد اجتياز المقابلة الشخصية.
                    </p>
                </div>
            </div>

            <div class="w-full md:w-7/12 p-6 sm:p-10 lg:p-12 flex flex-col justify-center bg-white relative">
                
                @if($isClosed)
                    <div class="text-center py-10">
                        <div class="w-24 h-24 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border-2 border-slate-100">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-3">عذراً، انتهى التقديم</h3>
                        <p class="text-slate-500 font-semibold text-sm sm:text-base leading-relaxed">تم اكتفاء العدد المطلوب أو إغلاق باب التقديم على هذه الوظيفة. نتمنى لك التوفيق في الفرص القادمة.</p>
                    </div>

                @elseif($success)
                    <div class="text-center py-10 animate-slide-up">
                        <div class="w-28 h-28 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-green-100 shadow-lg relative">
                            <div class="absolute inset-0 rounded-full border-4 border-green-400 animate-ping opacity-20"></div>
                            <svg class="w-14 h-14 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mb-4">تم استلام طلبك بنجاح!</h3>
                        <p class="text-slate-600 font-bold leading-relaxed bg-slate-50 p-5 rounded-2xl border border-slate-200 text-sm sm:text-base">
                            تم رفع سيرتك الذاتية بأمان. سيقوم نظامنا المدعوم بالذكاء الاصطناعي بمراجعة بياناتك، وسيتواصل معك فريقنا قريباً.
                        </p>
                    </div>

                @else
                    <div class="mb-8 text-center sm:text-right">
                        <h3 class="text-2xl font-black text-slate-800 mb-3 flex items-center justify-center sm:justify-start gap-2">
                            التقديم الذكي السريع ⚡
                        </h3>
                        <p class="text-sm text-slate-500 font-bold leading-relaxed bg-blue-50/50 p-4 rounded-xl border border-blue-100/50">
                            لا داعي لتعبئة أي نماذج! نظامنا المدعوم بالذكاء الاصطناعي سيقوم باستخراج بياناتك من السيرة الذاتية مباشرة.
                        </p>
                    </div>
                    
                    <form wire:submit.prevent="submitApplication" class="space-y-6 sm:space-y-8">
                        
                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-3">البريد الإلكتروني للتواصل *</label>
                            <input type="email" wire:model="email" required
                                class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl px-5 py-4 text-base focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-bold text-slate-800 shadow-sm" 
                                dir="ltr" placeholder="example@email.com">
                            @error('email') <span class="text-red-500 text-xs font-bold mt-2 block flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-black text-slate-700 mb-3">السيرة الذاتية (CV) *</label>
                            <div class="relative border-2 border-dashed border-blue-300 bg-blue-50/30 hover:bg-blue-50 rounded-3xl p-8 sm:p-10 text-center transition-all cursor-pointer group shadow-sm hover:border-blue-500 hover:shadow-md">
                                <input type="file" wire:model="cv_file" accept=".pdf,.doc,.docx" required
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div wire:loading.remove wire:target="cv_file, submitApplication">
                                    <div class="w-20 h-20 bg-white text-blue-600 rounded-2xl shadow-md flex items-center justify-center mx-auto mb-5 group-hover:-translate-y-1 group-hover:shadow-lg transition-all duration-300 border border-blue-100">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <p class="text-lg font-black text-slate-800">اضغط هنا لرفع السيرة الذاتية</p>
                                    <p class="text-xs text-slate-500 mt-2 font-bold bg-white inline-block px-3 py-1 rounded-full border border-slate-100 shadow-sm">PDF, DOCX (الحد الأقصى 5MB)</p>
                                </div>
                                
                                <div wire:loading wire:target="cv_file" class="text-blue-600 font-bold py-8">
                                    <div class="relative w-16 h-16 mx-auto mb-4">
                                        <div class="absolute inset-0 border-4 border-blue-200 rounded-full"></div>
                                        <div class="absolute inset-0 border-4 border-blue-600 rounded-full border-t-transparent animate-spin"></div>
                                    </div>
                                    جاري تجهيز الملف...
                                </div>
                            </div>
                            @error('cv_file') <span class="text-red-500 text-xs font-bold mt-2 block flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> {{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-blue-700 text-white font-black py-4 sm:py-5 rounded-2xl shadow-xl hover:shadow-blue-500/30 transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2 overflow-hidden relative active:scale-95 text-base sm:text-lg mt-4" wire:loading.attr="disabled" wire:target="submitApplication, cv_file">
                            
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>

                            <span wire:loading.remove wire:target="submitApplication">تأكيد التقديم للوظيفة</span>
                            
                            <span wire:loading wire:target="submitApplication" class="flex items-center gap-3">
                                <svg class="animate-spin h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                جاري تحليل البيانات بـ AI...
                            </span>
                        </button>
                        
                        <div class="mt-8 flex items-center justify-center gap-2 text-xs text-slate-400 font-bold bg-slate-50 p-3 rounded-xl">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            نضمن لك سرية بياناتك بالكامل.
                        </div>

                    </form>
                @endif
            </div>
        </div>
        
        <footer class="mt-8 pb-8 text-center text-slate-400 text-xs font-bold">
            <p>&copy; {{ date('Y') }} Hirehub Recruitment. جميع الحقوق محفوظة.</p>
            <p class="mt-2 flex items-center justify-center gap-1 bg-white inline-flex px-3 py-1.5 rounded-full shadow-sm">
                Powered by Advanced AI 
                <svg class="w-3.5 h-3.5 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            </p>
        </footer>

    </div>

    <style>
        @keyframes shimmer { 100% { transform: translateX(100%); } }
        .animate-slide-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</div>