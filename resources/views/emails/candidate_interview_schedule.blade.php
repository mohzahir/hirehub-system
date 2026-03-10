<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; color: #333; }
        .container { background-color: #ffffff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; border-top: 4px solid #10b981; }
        .header { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #047857; }
        .details { background-color: #ecfdf5; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">مرحباً {{ $candidate->first_name }} {{ $candidate->last_name }}،</div>
        <p>يسعدنا إخبارك بأنه تم ترشيحك لمقابلة عمل لوظيفة (<strong>{{ $project->title }}</strong>) عبر وكالة Hirehub.</p>
        
        <div class="details">
            <strong>📅 تاريخ المقابلة:</strong> {{ $interview_date }} <br>
            <strong>⏰ وقت المقابلة:</strong> {{ \Carbon\Carbon::parse($interview_time)->format('h:i A') }} <br>
            @if($interview_link)
                <strong>🔗 رابط/تفاصيل المقابلة:</strong> <a href="{{ $interview_link }}">{{ $interview_link }}</a>
            @endif
        </div>

        <p>يرجى الاستعداد للمقابلة والتواجد قبل الموعد بـ 10 دقائق.</p>
        <p style="margin-top: 20px; font-size: 12px; color: #666;">نتمنى لك التوفيق،<br>فريق التوظيف - Hirehub</p>
    </div>
</body>
</html>