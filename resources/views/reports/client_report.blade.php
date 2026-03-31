<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Comprehensive Client Report - {{ $client->company_name }}</title>
    <style>
        /* إضافة خط Dejavu Sans يدعم الرموز والحروف العربية لتجنب علامات الاستفهام ??? */
        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #3730a3;
            margin: 0 0 5px 0;
            font-size: 26px;
            text-transform: uppercase;
        }
        .header h2 {
            color: #4f46e5;
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            color: #64748b;
        }
        
        .project-section {
            margin-bottom: 40px;
            /* تم إزالة page-break-inside: avoid; من هنا لكي لا تفرغ الصفحة الأولى */
        }
        
        .project-header {
            background-color: #f1f5f9;
            padding: 10px 15px;
            border-left: 4px solid #4f46e5;
            margin-bottom: 15px;
            border-radius: 0 4px 4px 0;
            page-break-after: avoid; /* يمنع فصل العنوان عن الجدول الخاص به */
        }
        .project-header h3 {
            margin: 0 0 5px 0;
            color: #1e293b;
            font-size: 16px;
        }
        .project-header p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th {
            background-color: #4f46e5;
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
        /* تطبيق منع الانقسام على صفوف الجدول فقط لكي لا ينقسم المرشح الواحد بين صفحتين */
        .data-table tr {
            page-break-inside: avoid;
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
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        
        /* Status Colors */
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
        <h1>HireHub ATS</h1>
        <h2>Comprehensive Client Report: {{ $client->company_name }}</h2>
        <p>Location: {{ $client->city ?? 'N/A' }}, {{ $client->country }} | Generated on: {{ date('F j, Y') }}</p>
    </div>

    @forelse($client->projects as $project)
        <div class="project-section">
            <div class="project-header">
                <h3>Project: {{ $project->title }}</h3>
                <p>Required: {{ $project->required_count }} | Candidates Provided: {{ $project->applications->count() }} | Status: {{ ucfirst(str_replace('_', ' ', $project->status)) }}</p>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 20%;">Candidate Name</th>
                        <th style="width: 15%;">Nationality</th>
                        <th style="width: 20%;">Profession</th>
                        <th style="width: 15%;">Status</th>
                        <th style="width: 25%;">Client Feedback / Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->applications as $index => $app)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $app->candidate->first_name }} {{ $app->candidate->last_name }}</strong></td>
                            <td>{{ $app->candidate->nationality }}</td>
                            <td>
                                {{ $app->candidate->profession }}<br>
                                <span style="color: #64748b; font-size: 9px;">Exp: {{ $app->candidate->experience_years }} Yrs</span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $app->status }}">
                                    {{ $statusMap[$app->status] ?? $app->status }}
                                </span>
                            </td>
                            <td>
                                {{ $app->client_feedback ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px; color: #94a3b8;">No candidates in this project.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <div style="text-align: center; padding: 50px; color: #64748b; font-size: 16px;">
            This client currently has no registered projects.
        </div>
    @endforelse

    <div class="footer">
        Confidential Report - Generated automatically by HireHub ATS.
    </div>

</body>
</html>