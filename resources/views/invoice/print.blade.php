<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة توظيف - {{ $placement->application->candidate->first_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #e5e7eb; }
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        @media print {
            body { background: white; }
            .a4-page { margin: 0; box-shadow: none; border: none; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body class="text-gray-800">

    <div class="text-center my-4 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white font-bold py-2 px-6 rounded shadow hover:bg-blue-700">
            🖨️ طباعة / حفظ كملف PDF
        </button>
    </div>

    <div class="a4-page relative">
        
        <div class="flex justify-between items-start border-b-2 border-blue-600 pb-6 mb-8">
            <div>
                <h1 class="text-4xl font-black text-blue-800 mb-2">Hirehub</h1>
                <p class="text-gray-500 text-sm">وكالة توظيف احترافية</p>
                <p class="text-gray-500 text-sm">البريد: info@hirehub-sd.com</p>
            </div>
            <div class="text-left">
                <h2 class="text-3xl font-bold text-gray-300 mb-2">فاتورة / INVOICE</h2>
                <p class="font-bold text-gray-700">رقم الفاتورة: #INV-{{ str_pad($placement->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-gray-500">التاريخ: {{ now()->format('Y-m-d') }}</p>
            </div>
        </div>

        <div class="flex justify-between mb-8 bg-gray-50 p-4 rounded-lg">
            <div>
                <p class="text-sm text-gray-500 font-bold mb-1">مُصدرة إلى (Billed To):</p>
                @if($placement->fee_collector == 'hirehub' && !$placement->application->candidate->partner_id)
                    <h3 class="text-lg font-bold text-gray-800">المرشح: {{ $placement->application->candidate->first_name }} {{ $placement->application->candidate->last_name }}</h3>
                @elseif($placement->fee_collector == 'hirehub' && $placement->application->candidate->partner_id)
                    <h3 class="text-lg font-bold text-gray-800">المرشح: {{ $placement->application->candidate->first_name }} {{ $placement->application->candidate->last_name }}</h3>
                    <p class="text-sm text-gray-600">عبر مكتب: {{ $placement->application->candidate->partner->agency_name }}</p>
                @else
                    <h3 class="text-lg font-bold text-gray-800">مكتب: {{ $placement->application->candidate->partner->agency_name ?? 'الوكالة الشريكة' }}</h3>
                @endif
            </div>
            <div class="text-left">
                <p class="text-sm text-gray-500 font-bold mb-1">تفاصيل المشروع:</p>
                <p class="text-md font-bold text-gray-800">{{ $placement->application->project->title }}</p>
                <p class="text-sm text-gray-600">جهة العمل: {{ $placement->application->project->client->company_name ?? 'غير محدد' }}</p>
            </div>
        </div>

        <table class="w-full text-right border-collapse mb-8">
            <thead>
                <tr class="bg-blue-600 text-white">
                    <th class="py-3 px-4 font-bold border border-blue-700">الوصف</th>
                    <th class="py-3 px-4 font-bold border border-blue-700 text-center w-1/3">المبلغ (ريال)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @php
                        $target = $placement->fee_collector == 'hirehub' ? $placement->total_agency_fee : $placement->net_profit;
                    @endphp
                    <td class="py-4 px-4 border border-gray-200">
                        رسوم توظيف المرشح ({{ $placement->application->candidate->first_name }} {{ $placement->application->candidate->last_name }})
                        <br>
                        <span class="text-xs text-gray-500">المهنة: {{ $placement->application->candidate->profession }}</span>
                    </td>
                    <td class="py-4 px-4 border border-gray-200 text-center font-bold text-lg">
                        {{ number_format($target, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end mb-12">
            <div class="w-1/2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex justify-between border-b pb-2 mb-2">
                    <span class="font-bold text-gray-600">إجمالي المطلوب:</span>
                    <span class="font-bold text-gray-800">{{ number_format($target, 2) }} ريال</span>
                </div>
                <div class="flex justify-between border-b pb-2 mb-2">
                    <span class="font-bold text-gray-600">ما تم دفعه (سند قبض):</span>
                    <span class="font-bold text-green-600">- {{ number_format($placement->amount_paid, 2) }} ريال</span>
                </div>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-bold text-xl text-gray-800">المتبقي للدفع:</span>
                    <span class="font-black text-2xl {{ ($target - $placement->amount_paid) == 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($target - $placement->amount_paid, 2) }} ريال
                    </span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-end mt-20 pt-8 border-t border-gray-200">
            <div class="text-center">
                <p class="font-bold text-gray-800 mb-8">توقيع المستلم / المحاسب</p>
                <div class="border-b-2 border-gray-400 w-48 mx-auto"></div>
            </div>
            
            <div class="text-center opacity-70">
                <div class="w-32 h-32 border-4 border-blue-600 rounded-full flex items-center justify-center text-blue-600 font-bold transform -rotate-12 mx-auto">
                    {{ ($target - $placement->amount_paid) == 0 ? 'PAID - مدفوع' : 'OFFICIAL INVOICE' }}
                </div>
            </div>
        </div>

        <div class="absolute bottom-10 left-0 right-0 text-center text-xs text-gray-400">
            هذه الفاتورة تم إصدارها إلكترونياً عبر نظام Hirehub ولا تحتاج لختم يدوي في حال وجود التحويل البنكي المطابق.
        </div>

    </div>

    <script>
        // تشغيل نافذة الطباعة/الحفظ تلقائياً عند فتح الصفحة
        window.onload = function() {
            setTimeout(function() { window.print(); }, 500);
        }
    </script>
</body>
</html>