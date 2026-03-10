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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Candidate Profile</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $application->project->client->company_name }}</strong> Team,</p>
            <p>Greetings,</p>
            <p>Based on your request for the <strong>({{ $application->project->title }})</strong> position, we are pleased to submit the profile of a matching candidate:</p>
            
            <ul>
                <li><strong>Profession:</strong> {{ $application->candidate->profession }}</li>
                <li><strong>Nationality:</strong> {{ $application->candidate->nationality }}</li>
                <li><strong>Experience:</strong> {{ $application->candidate->experience_years }} Years</li>
            </ul>

            <p>Please find the candidate's CV attached for your review.</p>
            <p>We look forward to receiving your feedback to schedule an interview if the candidate meets your requirements.</p>
            
            <p>Best Regards,<br>Hirehub Recruitment Team</p>
        </div>
        <div class="footer">
            This is an automated message from Hirehub Recruitment System.
        </div>
    </div>
</body>
</html>