<div class="min-h-screen bg-[#f8fafc] font-sans relative selection:bg-blue-600 selection:text-white pb-12">
    
    <div class="absolute top-0 left-0 right-0 h-96 bg-gradient-to-b from-blue-900 to-slate-900 z-0 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
        
        <header class="flex justify-between items-center mb-10 text-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white text-blue-700 rounded-xl shadow-lg flex items-center justify-center transform -rotate-6">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-wide">Hirehub</h1>
                    <p class="text-[10px] text-blue-200 tracking-widest uppercase">للاستشارات والتوظيف</p>
                </div>
            </div>
            
            <div class="hidden sm:flex items-center gap-2 text-sm font-semibold bg-white/10 px-4 py-2 rounded-full backdrop-blur-md border border-white/20">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                بوابة التقديم الآمنة
            </div>
        </header>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-slate-100">
            
            <div class="w-full md:w-5/12 bg-slate-50 p-8 lg:p-10 border-b md:border-b-0 md:border-l border-slate-200 flex flex-col">
                
                <div class="mb-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full mb-4 border border-green-200 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        توظيف عاجل
                    </span>
                    <h2 class="text-3xl font-black text-slate-800 leading-tight mb-3">{{ $project->title }}</h2>
                    
                    <div class="flex items-center gap-2 text-slate-500 font-semibold bg-white px-3 py-2 rounded-lg border border-slate-200 shadow-sm w-max">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-5 11h3m-3 4.5V19"></path></svg>
                        لصالح إحدى الجهات الكبرى
                    </div>
                </div>

                @if($project->description)
                    <div class="mb-6 flex-1">
                        <h4 class="font-bold text-slate-800 mb-3 text-sm flex items-center gap-2 border-b border-slate-200 pb-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            الوصف والمتطلبات:
                        </h4>
                        <div class="text-sm leading-relaxed text-slate-600 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                            {{ $project->description }}
                        </div>
                    </div>
                @endif
                
                <div class="mt-auto bg-blue-50 border border-blue-100 p-4 rounded-xl text-center shadow-inner">
                    <div class="flex justify-center mb-2">
                        <div class="bg-blue-100 p-2 rounded-full text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-blue-800 text-xs font-bold leading-relaxed">
                        يتم تحديد الراتب الإجمالي والمزايا وبدلات السكن بناءً على الخبرة بعد اجتياز المقابلة الشخصية.
                    </p>
                </div>
            </div>

            <div class="w-full md:w-7/12 p-8 lg:p-12 flex flex-col justify-center bg-white relative">
                
                @if($isClosed)
                    <div class="text-center">
                        <div class="w-24 h-24 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">عذراً، انتهى التقديم</h3>
                        <p class="text-slate-500 font-semibold">تم اكتفاء العدد المطلوب أو إغلاق باب التقديم على هذه الوظيفة. نتمنى لك التوفيق.</p>
                    </div>

                @elseif($success)
                    <div class="text-center animate-pulse-once">
                        <div class="w-28 h-28 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 border-4 border-green-100 shadow-lg">
                            <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-slate-800 mb-3">تم استلام طلبك بنجاح!</h3>
                        <p class="text-slate-600 font-medium leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                            تم رفع سيرتك الذاتية بأمان. سيقوم نظامنا الذكي بقراءة بياناتك، وسيتواصل معك فريق التوظيف قريباً في حال تطابق خبراتك مع الشروط.
                        </p>
                    </div>

                @else
                    <div class="mb-8">
                        <h3 class="text-2xl font-black text-slate-800 mb-2 flex items-center gap-2">
                            التقديم الذكي السريع ⚡
                        </h3>
                        <p class="text-sm text-slate-500 font-semibold">
                            لا داعي لتعبئة نماذج طويلة! نظامنا المدعوم بالذكاء الاصطناعي سيقوم باستخراج بياناتك من السيرة الذاتية مباشرة.
                        </p>
                    </div>
                    
                    <form wire:submit.prevent="submitApplication">
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني للتواصل *</label>
                            <input type="email" wire:model="email" required
                                class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-3.5 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all font-semibold text-slate-800" 
                                dir="ltr" placeholder="example@email.com">
                            @error('email') <span class="text-red-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-slate-700 mb-2">السيرة الذاتية (CV) *</label>
                            <div class="relative border-2 border-dashed border-blue-300 bg-blue-50/50 hover:bg-blue-50 rounded-2xl p-8 text-center transition-all cursor-pointer group shadow-sm hover:border-blue-500">
                                <input type="file" wire:model="cv_file" accept=".pdf,.doc,.docx" required
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                
                                <div wire:loading.remove wire:target="cv_file, submitApplication">
                                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    </div>
                                    <p class="text-base font-black text-slate-700">اضغط هنا أو اسحب ملف السيرة الذاتية</p>
                                    <p class="text-xs text-slate-500 mt-2 font-semibold">يقبل صيغ PDF, DOCX (الحد الأقصى 5MB)</p>
                                </div>
                                
                                <div wire:loading wire:target="cv_file" class="text-blue-600 font-bold py-6">
                                    <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    جاري رفع الملف وتهيئته...
                                </div>
                            </div>
                            @error('cv_file') <span class="text-red-500 text-xs font-bold mt-1.5 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full bg-slate-900 hover:bg-blue-700 text-white font-black py-4 rounded-xl shadow-xl hover:shadow-blue-500/30 transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2 overflow-hidden relative" wire:loading.attr="disabled" wire:target="submitApplication, cv_file">
                            
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>

                            <span wire:loading.remove wire:target="submitApplication">تأكيد التقديم للوظيفة</span>
                            
                            <span wire:loading wire:target="submitApplication" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                جاري تحليل البيانات بالذكاء الاصطناعي...
                            </span>
                        </button>
                        
                        <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-400 font-semibold">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            نضمن لك سرية بياناتك بالكامل. لن يتم مشاركتها إلا مع جهة التوظيف.
                        </div>

                    </form>
                @endif
            </div>
        </div>
        
        <footer class="mt-12 text-center text-slate-400 text-xs font-semibold">
            <p>&copy; {{ date('Y') }} Hirehub Recruitment. جميع الحقوق محفوظة.</p>
            <p class="mt-1 flex items-center justify-center gap-1">
                Powered by Advanced AI 
                <svg class="w-3 h-3 text-purple-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
            </p>
        </footer>

    </div>

    <style>
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }
    </style>
</div>