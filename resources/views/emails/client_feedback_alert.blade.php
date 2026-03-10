<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px; color: #333; }
        .container { background-color: #ffffff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; border-top: 4px solid #2563eb; }
        .header { font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        .badge-green { background-color: #d1fae5; color: #065f46; padding: 5px 10px; border-radius: 4px; font-weight: bold; }
        .badge-red { background-color: #fee2e2; color: #991b1b; padding: 5px 10px; border-radius: 4px; font-weight: bold; }
        .details { background-color: #f8fafc; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">🔔 تنبيه: تفاعل جديد من العميل!</div>
        <p>لقد قام فريق (<strong>{{ $application->project->client->company_name ?? 'العميل' }}</strong>) بمراجعة أحد المرشحين في مشروع: {{ $application->project->title }}.</p>
        
        <div class="details">
            <p><strong>المرشح:</strong> {{ $application->candidate->first_name }} {{ $application->candidate->last_name }}</p>
            <p><strong>المهنة:</strong> {{ $application->candidate->profession }}</p>
            
            <p><strong>القرار:</strong> 
                @if($action == 'مقبول للمقابلة')
                    <span class="badge-green">مقبول للمقابلة ✅</span>
                @else
                    <span class="badge-red">تم الاستبعاد ❌</span>
                @endif
            </p>

            <p><strong>تعليق العميل:</strong> <br>
               <span style="color: #475569; font-style: italic;">"{{ $application->client_feedback }}"</span>
            </p>
        </div>

        <p style="font-size: 14px;">يرجى الدخول إلى نظام Hirehub لاتخاذ الإجراء المناسب (مثل جدولة المقابلة).</p>
        <p style="margin-top: 20px; font-size: 12px; color: #94a3b8;">إشعار تلقائي من نظام Hirehub ATS.</p>
    </div>
</body>
</html>