<div class="p-4 sm:p-6 bg-slate-50 min-h-screen space-y-6">

    <div class="flex justify-between items-center mb-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800">صباح الخير، {{ auth()->user()->name ?? 'مدير النظام' }} 👋</h1>
            <p class="text-sm text-slate-500 mt-1">إليك الملخص الشامل لأداء وكالة Hirehub.</p>
        </div>
        <div class="text-left hidden sm:block bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-100">
            <p class="text-xs text-slate-400 font-bold">تاريخ اليوم</p>
            <p class="text-sm text-slate-800 font-bold">{{ now()->translatedFormat('l، j F Y') }}</p>
        </div>
    </div>

    @if(count($smartAlerts) > 0)
        <div class="mb-8">
            <h3 class="text-sm font-bold text-slate-500 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                مساعد Hirehub الذكي
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($smartAlerts as $alert)
                    <div class="{{ $alert['bgColor'] }} border {{ $alert['borderColor'] }} rounded-xl p-4 flex gap-4 shadow-sm hover:shadow-md transition">
                        <div class="text-3xl {{ $alert['iconColor'] }} bg-white h-12 w-12 flex items-center justify-center rounded-full shadow-sm">
                            {{ $alert['icon'] }}
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold {{ $alert['textColor'] }} text-sm mb-1">{{ $alert['title'] }}</h4>
                            <p class="{{ $alert['textColor'] }} opacity-80 text-xs leading-relaxed">
                                {{ $alert['message'] }}
                            </p>
                            @if($alert['link'] !== '/')
                                <a href="{{ $alert['link'] }}" class="mt-2 inline-block text-[10px] font-bold {{ $alert['textColor'] }} underline hover:opacity-70">
                                    اتخاذ إجراء &larr;
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-gradient-to-r from-blue-900 to-indigo-800 rounded-2xl p-6 shadow-xl text-white">
        <h2 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            الأرقام الإجمالية (منذ التأسيس)
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 divide-y sm:divide-y-0 sm:divide-x sm:divide-x-reverse divide-blue-700">
            <div class="pt-4 sm:pt-0 pl-0 sm:pl-4">
                <p class="text-blue-200 text-xs sm:text-sm mb-1">إجمالي الفواتير (الإيرادات)</p>
                <p class="text-xl sm:text-2xl font-bold">{{ number_format($agencyTotalRevenue) }} <span class="text-[10px] sm:text-xs text-blue-300">ريال</span></p>
            </div>
            <div class="pt-4 sm:pt-0 pr-0 sm:pr-4 pl-0 sm:pl-4">
                <p class="text-blue-200 text-xs sm:text-sm mb-1">صافي أرباح الوكالة</p>
                <p class="text-xl sm:text-2xl font-bold text-green-400">{{ number_format($agencyNetProfit) }} <span class="text-[10px] sm:text-xs text-green-200">ريال</span></p>
            </div>
            <div class="pt-4 sm:pt-0 pr-0 sm:pr-4 pl-0 sm:pl-4">
                <p class="text-blue-200 text-xs sm:text-sm mb-1">المدفوع للمكاتب الشريكة</p>
                <p class="text-xl sm:text-2xl font-bold text-orange-300">{{ number_format($totalPartnerFees) }} <span class="text-[10px] sm:text-xs text-orange-200">ريال</span></p>
            </div>
            <div class="pt-4 sm:pt-0 pr-0 sm:pr-4">
                <p class="text-blue-200 text-xs sm:text-sm mb-1">عمليات التوظيف الناجحة</p>
                <p class="text-xl sm:text-2xl font-bold text-white">{{ $totalPlacements }} <span class="text-[10px] sm:text-xs text-blue-300">مرشح</span></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 w-2 h-full bg-emerald-500"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">أرباح الشهر الحالي</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ number_format($profitThisMonth) }} <span class="text-sm font-bold text-slate-400">ريال</span></h3>
                </div>
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-sm font-bold">
                @if($profitChange >= 0)
                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg> +{{ number_format($profitChange, 1) }}%</span>
                @else
                    <span class="text-red-600 bg-red-50 px-2 py-0.5 rounded flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"></path></svg> {{ number_format($profitChange, 1) }}%</span>
                @endif
                <span class="text-slate-400 text-xs">عن الشهر الماضي</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 w-2 h-full bg-blue-500"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">مرشحون قيد الإجراء</p>
                    <h3 class="text-3xl font-black text-slate-800">{{ $activeCandidatesCount }}</h3>
                </div>
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4 text-xs font-bold text-slate-400">موزعين في مراحل الفرز والمقابلات.</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 relative overflow-hidden group hover:shadow-md transition">
            <div class="absolute right-0 top-0 w-2 h-full bg-amber-500"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-bold text-slate-500 mb-1">المكتب الشريك الأفضل</p>
                    <h3 class="text-lg sm:text-xl font-black text-slate-800 truncate">{{ $topPartner ? $topPartner->agency_name : 'لا يوجد' }}</h3>
                </div>
                <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
            </div>
            <div class="mt-4 text-sm font-bold">
                @if($topPartner)
                    <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded">تم توريد {{ $topPartner->candidates_count }} مرشح</span>
                @else
                    <span class="text-slate-400 text-xs">لم يتم تسجيل مكاتب نشطة.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mt-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-6 lg:col-span-2">
            <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                نمو الأرباح (آخر 6 أشهر)
            </h3>
            <div id="revenueChart" class="w-full h-72"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-6 lg:col-span-1 flex flex-col">
            <h3 class="text-lg font-black text-slate-800 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                قمع التوظيف (كفاءة الأداء)
            </h3>
            <p class="text-xs text-slate-400 mb-4">يعرض تسرب المرشحين في كل مرحلة.</p>
            <div id="funnelChart" class="w-full flex-1"></div>
        </div>
    </div>

    <h3 class="text-xl font-bold text-gray-800 mt-8 mb-4 border-b pb-2">أداء فريق التوظيف والمبيعات</h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($recruiters as $recruiter)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex flex-col hover:shadow-md transition">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xl font-bold uppercase shadow-inner">
                            {{ substr($recruiter->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-base sm:text-lg">{{ $recruiter->name }}</h4>
                            <p class="text-xs text-gray-500">أخصائي توظيف</p>
                        </div>
                    </div>
                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">
                        العمولة: {{ $recruiter->commission_rate ?? 5 }}%
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 flex-1">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 text-center sm:text-right">
                        <p class="text-[10px] sm:text-xs text-gray-500 font-semibold mb-1">إغلاق الشواغر</p>
                        <p class="font-bold text-gray-800 text-lg sm:text-xl">{{ $recruiter->placements_count }} <span class="text-[10px] font-normal text-gray-400">مرشحين</span></p>
                    </div>
                    <div class="bg-blue-50/50 p-3 rounded-lg border border-blue-100 text-center sm:text-right">
                        <p class="text-[10px] sm:text-xs text-blue-800 font-semibold mb-1" title="صافي الأرباح التي أدخلها هذا الموظف للوكالة">أرباح حققها للوكالة</p>
                        <p class="font-bold text-blue-700 text-lg sm:text-xl">{{ number_format($recruiter->total_sales) }} <span class="text-[10px] font-normal text-blue-400">ريال</span></p>
                    </div>
                    
                    <div class="bg-green-50 p-3 sm:p-4 rounded-lg border border-green-200 col-span-2 flex justify-between items-center shadow-sm">
                        <span class="text-xs sm:text-sm text-green-800 font-bold">إجمالي العمولة المستحقة له:</span>
                        <span class="font-extrabold text-green-700 text-xl sm:text-2xl">{{ number_format($recruiter->total_commission) }} <span class="text-xs font-normal">ريال</span></span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-6 mt-6">
        <div class="flex justify-between items-center mb-6 pb-2 border-b border-slate-100">
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                جدول مقابلات اليوم
            </h3>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-3 py-1 rounded-full">{{ count($todaysInterviews) }} موعد مجدول</span>
        </div>

        @if(count($todaysInterviews) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @foreach($todaysInterviews as $app)
                    <div class="bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 hover:shadow-md transition group">
                        <div class="flex justify-between items-start mb-2">
                            <span class="bg-white text-indigo-700 font-black text-sm px-2 py-1 rounded shadow-sm border border-indigo-50">
                                {{ \Carbon\Carbon::parse($app->interview_time)->format('h:i A') }}
                            </span>
                            <span class="text-[10px] text-slate-500 bg-white px-2 py-0.5 rounded-full border truncate max-w-[100px]">{{ $app->project->client->company_name ?? 'العميل' }}</span>
                        </div>
                        <h4 class="font-bold text-slate-800 text-base mt-2">{{ $app->candidate->first_name }} {{ $app->candidate->last_name }}</h4>
                        <p class="text-xs text-slate-500 mt-1">{{ $app->candidate->profession }}</p>
                        
                        @if($app->interview_link)
                            <a href="{{ str_starts_with($app->interview_link, 'http') ? $app->interview_link : 'https://' . $app->interview_link }}" target="_blank" class="mt-4 w-full text-center text-xs bg-indigo-600 text-white font-bold px-3 py-2 rounded-lg inline-block hover:bg-indigo-700 transition">
                                الدخول لرابط المقابلة
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-slate-400 py-8 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm font-bold text-slate-500">لا توجد مقابلات مجدولة لهذا اليوم.</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            // 1. الرسم البياني للأرباح (Line Chart)
            var revenueOptions = {
                series: [{ name: 'صافي الأرباح', data: {!! $chartData !!} }],
                chart: { type: 'area', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
                colors: ['#3b82f6'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] } },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: { categories: {!! $chartLabels !!}, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { labels: { formatter: function (value) { return value + " ريال"; } } },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } }
            };
            var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();

            // 2. قمع التوظيف (Funnel Chart)
            var funnelOptions = {
                series: [{ name: "العدد", data: {!! $funnelData !!} }],
                chart: { type: 'bar', height: 280, fontFamily: 'inherit', toolbar: { show: false } },
                plotOptions: {
                    bar: { borderRadius: 0, horizontal: true, barHeight: '80%', isFunnel: true },
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opt) { return opt.w.globals.labels[opt.dataPointIndex] + ':  ' + val; },
                    dropShadow: { enabled: true, top: 1, left: 1, blur: 1, opacity: 0.5 }
                },
                colors: ['#8b5cf6'],
                xaxis: { categories: [] },
                legend: { show: false },
                tooltip: { y: { formatter: function (val) { return val + " مرشح"; } } }
            };
            var funnelChart = new ApexCharts(document.querySelector("#funnelChart"), funnelOptions);
            funnelChart.render();
        });
    </script>
</div>