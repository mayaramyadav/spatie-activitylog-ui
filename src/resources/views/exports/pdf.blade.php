<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 1.5cm;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 3px solid #f4f4f5;
            padding-bottom: 25px;
        }

        .header h1 {
            margin: 0;
            font-size: 22pt;
            letter-spacing: -0.02em;
            color: #09090b;
            font-weight: 900;
            text-transform: uppercase;
        }

        .meta-info {
            margin-bottom: 30px;
            background: #fafafa;
            padding: 20px;
            border-radius: 10px;
        }

        .meta-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-info td {
            padding: 5px 0;
            vertical-align: top;
            font-size: 9pt;
        }

        .meta-info .label {
            font-weight: 800;
            width: 120px;
            color: #71717a;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 7pt;
        }

        .activities-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .activities-table th {
            background-color: #09090b;
            color: #ffffff;
            font-weight: 800;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 12px 10px;
            text-align: left;
            border: none;
        }

        .activities-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f4f4f5;
            vertical-align: top;
            font-size: 8pt;
        }

        .event-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 7pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .event-created { background-color: #ecfdf5; color: #065f46; }
        .event-updated { background-color: #eff6ff; color: #1e40af; }
        .event-deleted { background-color: #fef2f2; color: #991b1b; }
        .event-restored { background-color: #fffbeb; color: #92400e; }
        .event-login { background-color: #f5f3ff; color: #5b21b6; }
        .event-logout { background-color: #f5f3ff; color: #5b21b6; }
        .event-system { background-color: #faf5ff; color: #6b21a8; }
        .event-default { background-color: #f4f4f5; color: #27272a; }

        .id-cell { font-family: monospace; color: #71717a; }
        .date-cell { white-space: nowrap; font-weight: 600; }
        .user-cell { font-weight: 700; color: #09090b; }
        
        .description {
            color: #3f3f46;
            line-height: 1.4;
        }

        .changes {
            font-size: 7pt;
            color: #71717a;
            font-style: italic;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            font-size: 7pt;
            color: #a1a1aa;
            border-top: 1px solid #f4f4f5;
            padding-top: 15px;
        }

        .page-number:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Activity Report</h1>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td class="label">Date Generated</td>
                <td>{{ $generated_at->format('F j, Y \a\t g:i A T') }}</td>
            </tr>
            <tr>
                <td class="label">Volume</td>
                <td>{{ number_format($total_count) }} Records Extracted</td>
            </tr>
            @if(!empty($filters))
            <tr>
                <td class="label">Constraints</td>
                <td>
                    @foreach($filters as $key => $value)
                        @if($value)
                            <span style="color: #71717a;">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                            <span style="font-weight: 600;">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                            @if(!$loop->last) &bull; @endif
                        @endif
                    @endforeach
                </td>
            </tr>
            @endif
        </table>
    </div>

    <table class="activities-table">
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 15%;">Timestamp</th>
                <th style="width: 15%;">Originator</th>
                <th style="width: 10%;">Signature</th>
                <th style="width: 15%;">Resource</th>
                <th style="width: 25%;">Insight</th>
                <th style="width: 15%;">Delta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
            <tr>
                <td class="id-cell">#{{ $activity->id }}</td>
                <td class="date-cell">{{ $activity->created_at->format('M j, Y') }}<br/><span style="font-size: 7pt; color: #71717a;">{{ $activity->created_at->format('H:i:s') }}</span></td>
                <td class="user-cell">{{ $activity->causer_name ?? 'System Automated' }}</td>
                <td>
                    <span class="event-badge event-{{ $activity->event ?? 'default' }}">
                        {{ $activity->event ?? 'unknown' }}
                    </span>
                </td>
                <td>
                    @if($activity->subject_type)
                        <span style="font-weight: 700; color: #09090b;">{{ class_basename($activity->subject_type) }}</span><br/>
                        <span style="font-size: 7pt; color: #71717a;">Ref #{{ $activity->subject_id }}</span>
                    @else
                        <span style="color: #d1d5db;">N/A</span>
                    @endif
                </td>
                <td class="description">{{ $activity->description }}</td>
                <td class="changes">
                    @if($activity->hasPropertyChanges())
                        {{ $activity->getChangesSummary() }}
                    @else
                        Stateless
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 60px; color: #a1a1aa; font-style: italic;">
                    No activity telemetry found matching the current filtration parameters.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>
            Consolidated telemetric audit log &bull; Page <span class="page-number"></span> &bull; 
            Generated on {{ $generated_at->format('Y-m-d H:i') }}
        </p>
    </div>
</body>
</html>
