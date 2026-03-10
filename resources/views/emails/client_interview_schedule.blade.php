<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; color: #333; }
        .container { background-color: #ffffff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; border-top: 4px solid #4f46e5; }
        .header { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #1e3a8a; }
        .details { background-color: #eef2ff; padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
        table { w-full; border-collapse: collapse; margin-top: 10px; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: right; }
        th { background-color: #f3f4f6; }
        .btn { display: inline-block; padding: 10px 15px; background-color: #4f46e5; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">مرحباً بفريق {{ $project->client->company_name ?? 'العميل' }}،</div>
        <p>تمت جدولة مقابلات لعدد ({{ count($candidates) }}) مرشحين بخصوص مشروع التوظيف: <strong>{{ $project->title }}</strong>.</p>
        
        <div class="details">
            <strong>📅 تاريخ المقابلة:</strong> {{ $interview_date }} <br>
            <strong>⏰ وقت المقابلة:</strong> {{ \Carbon\Carbon::parse($interview_time)->format('h:i A') }} <br>
            @if($interview_link)
                <strong>🔗 الرابط / المكان:</strong> <a href="{{ $interview_link }}">{{ $interview_link }}</a>
            @endif
        </div>

        <h3>قائمة المرشحين:</h3>
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>المهنة</th>
                    <th>الجنسية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($candidates as $cand)
                <tr>
                    <td>{{ $cand->first_name }} {{ $cand->last_name }}</td>
                    <td>{{ $cand->profession }}</td>
                    <td>{{ $cand->nationality }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="margin-top: 20px; font-size: 12px; color: #666;">مع تحيات فريق Hirehub.</p>
    </div>
</body>
</html>