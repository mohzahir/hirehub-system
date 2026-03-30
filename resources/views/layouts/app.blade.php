<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Hirehub - نظام إدارة التوظيف' }}</title>

    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563eb">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased flex h-screen overflow-hidden bg-slate-50 text-slate-900" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" style="display: none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 right-0 z-50 w-64 bg-slate-900 text-white flex flex-col shadow-2xl transition-transform duration-300 lg:static lg:flex border-l border-slate-800">
        
        <div class="p-6 border-b border-slate-800 flex justify-between items-center lg:justify-center">
            <div class="text-center w-full">
                <h1 class="text-3xl font-black tracking-wider text-white flex items-center justify-center gap-2">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Hire<span class="text-blue-500">hub</span>
                </h1>
                <p class="text-[10px] text-slate-400 mt-1 tracking-widest uppercase">نظام إدارة التوظيف</p>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-grow p-4 space-y-6 overflow-y-auto custom-scrollbar">
            
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-500 mb-2 uppercase tracking-wider">العمليات الأساسية</p>
                <ul class="space-y-1">
                    <li>
                        <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->is('dashboard') || request()->is('dashboard/*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            لوحة القيادة الشاملة
                        </a>
                    </li>
                    <li>
                        <a href="/projects" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->is('projects') || request()->is('project/*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            المشاريع والتوظيف
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <p class="px-3 text-[10px] font-bold text-slate-500 mb-2 uppercase tracking-wider">علاقات الأعمال</p>
                <ul class="space-y-1">
                    <li>
                        <a href="/clients" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->is('clients') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-10V4m0 10V4m-5 11h3m-3 4.5V19"></path></svg>
                            العملاء (المستشفيات)
                        </a>
                    </li>
                    <li>
                        <a href="/partners" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->is('partners') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            المكاتب الشريكة
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <p class="px-3 text-[10px] font-bold text-slate-500 mb-2 uppercase tracking-wider">التقارير والمتابعة</p>
                <ul class="space-y-1">
                    <li>
                        <a href="/reports" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->is('reports') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            لوحة التقارير
                        </a>
                    </li>
                </ul>
            </div>

            @can('is-admin')
            <div>
                <p class="px-3 text-[10px] font-bold text-slate-500 mb-2 uppercase tracking-wider">الإدارة المالية</p>
                <ul class="space-y-1">
                    <li>
                        <a href="/collections" class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors duration-200 {{ request()->is('collections') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                التحصيلات المالية
                            </div>
                        </a>
                    </li>
                    </ul>
            </div>
            @endcan
        </nav>

        <div class="p-4 border-t border-slate-800 bg-slate-900/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-red-400 bg-red-400/10 hover:bg-red-500 hover:text-white rounded-lg transition font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    تسجيل الخروج
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden w-full relative">
        
        <header class="bg-white shadow-sm border-b border-slate-200 p-4 flex justify-between items-center z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-blue-600 focus:outline-none p-1 rounded-md hover:bg-slate-100 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-xl font-bold text-slate-800 truncate">
                    {{ $title ?? 'لوحة التحكم' }}
                </h2>
            </div>
            
            <div class="flex items-center gap-4">
                <button class="text-slate-400 hover:text-blue-600 transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>

                <div class="flex items-center gap-3 pl-2 border-l border-slate-200">
                    <div class="text-left hidden sm:block">
                        <p class="text-sm font-bold text-slate-700 leading-tight">
                            {{ Auth::user()->name ?? 'مستخدم النظام' }}
                        </p>
                        <p class="text-[10px] text-slate-500">{{ Auth::user()->role === 'admin' ? 'المدير العام' : 'أخصائي توظيف' }}</p>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-full text-white font-bold flex items-center justify-center text-sm sm:text-base uppercase shadow-md border-2 border-white">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50">
            {{ $slot }}
        </div>
        
    </main>

    <style>
        /* تخصيص شريط التمرير (Scrollbar) للقائمة الجانبية لتكون أنيقة */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #475569; }
    </style>


    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, err => {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }
    </script>
</body>
</html>