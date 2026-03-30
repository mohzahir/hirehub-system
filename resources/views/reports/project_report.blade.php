<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Status Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #1e3a8a;
            margin: 0 0 5px 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            color: #64748b;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
        }
        .info-table td.label {
            font-weight: bold;
            background-color: #f8fafc;
            width: 20%;
            color: #334155;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        .data-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .status-badge {
            font-weight: bold;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            text-transform: uppercase;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
        /* Status Colors mapping */
        .status-received { color: #475569; }
        .status-shortlisted { color: #0284c7; }
        .status-sent_to_client { color: #059669; }
        .status-interviewing { color: #7c3aed; }
        .status-accepted { color: #16a34a; }
        .status-rejected { color: #dc2626; }
    </style>
</head>
<body>

    @php
        $statusMap = [
            'received' => 'Received',
            'shortlisted' => 'Shortlisted',
            'sent_to_client' => 'Sent to Client',
            'interviewing' => 'Interviewing',
            'accepted' => 'Accepted',
            'rejected' => 'Rejected',
        ];
    @endphp

    <div class="header">
        <h1>HireHub - Project Status Report</h1>
        <p>Generated on: {{ date('F j, Y, g:i a') }}</p>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">Project Title:</td>
                <td><strong>{{ $project->title }}</strong></td>
                <td class="label">Client / Hospital:</td>
                <td>{{ $project->client->company_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Required Count:</td>
                <td>{{ $project->required_count }} Candidates</td>
                <td class="label">Total Candidates Provided:</td>
                <td>{{ $project->applications->count() }} Candidates</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Candidate Name</th>
                <th>Nationality</th>
                <th>Profession / Specialty</th>
                <th>Exp. (Yrs)</th>
                <th>Current Status</th>
                <th>Interview Schedule</th>
                <th>Client Feedback / Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($project->applications as $index => $app)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $app->candidate->first_name }} {{ $app->candidate->last_name }}</strong></td>
                    <td>{{ $app->candidate->nationality }}</td>
                    <td>{{ $app->candidate->profession }}</td>
                    <td style="text-align: center;">{{ $app->candidate->experience_years }}</td>
                    <td>
                        <span class="status-badge status-{{ $app->status }}">
                            {{ $statusMap[$app->status] ?? $app->status }}
                        </span>
                    </td>
                    <td>
                        @if($app->interview_date)
                            {{ \Carbon\Carbon::parse($app->interview_date)->format('M d, Y') }}<br>
                            <small>{{ \Carbon\Carbon::parse($app->interview_time)->format('h:i A') }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{ $app->client_feedback ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No candidates available for this project yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Confidential Report - Generated automatically by HireHub ATS.
    </div>

</body>
</html>