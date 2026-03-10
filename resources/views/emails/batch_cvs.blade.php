<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background-color: #1e3a8a; color: white; padding: 15px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Candidates Shortlist</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $project->client->company_name }}</strong> Team,</p>
            <p>Greetings,</p>
            <p>Please find attached the CVs for ({{ $applications->count() }}) shortlisted candidates for the <strong>({{ $project->title }})</strong> position.</p>
            
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Profession</th>
                        <th>Nationality</th>
                        <th>Experience</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                    <tr>
                        <td>{{ $app->candidate->first_name }}</td>
                        <td>{{ $app->candidate->profession }}</td>
                        <td>{{ $app->candidate->nationality }}</td>
                        <td>{{ $app->candidate->experience_years }} Years</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p>Please review the attachments, and we look forward to your feedback to proceed with the interviews.</p>
            
            <p>Best Regards,<br>Hirehub Recruitment Team</p>
        </div>
        <div class="footer">
            Hirehub Recruitment System - Automated Message
        </div>
    </div>
</body>
</html>