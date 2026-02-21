<!DOCTYPE html>
<html>
<head>
    <title>Monthly Report</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .stat-box { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monthly NOC Report</h1>
        <p>Month: {{ $summary->month }}</p>
    </div>

    <div class="stat-box">
        <h3>Summary Statistics</h3>
        <table>
            <tr>
                <th>Total Incidents</th>
                <td>{{ $summary->total_incident }}</td>
            </tr>
            <tr>
                <th>Total Downtime</th>
                <td>{{ $summary->total_downtime }} minutes</td>
            </tr>
            <tr>
                <th>SLA Percentage</th>
                <td>{{ $summary->sla_percentage }}%</td>
            </tr>
            <tr>
                <th>New Activations</th>
                <td>{{ $summary->total_activation }}</td>
            </tr>
            <tr>
                <th>Upgrades</th>
                <td>{{ $summary->total_upgrade }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('Y-m-d H:i') }}</p>
    </div>
</body>
</html>
