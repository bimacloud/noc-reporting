<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Backbone Capacity Log</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Track upgrades and downgrades of backbone links</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('backbone_logs.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Record Change
            </a>
            @endif
        </div>
    </x-slot>

    @php
    if (!function_exists('formatBw')) {
        function formatBw($val) {
            if (!$val) return '—';
            if (preg_match('/[a-zA-Z]/', $val)) return $val;
            $num = (float) $val;
            if ($num >= 1000) {
                $g = $num / 1000;
                return ($g == floor($g) ? $g : number_format($g, 2)) . 'G';
            }
            return $num . ' Mbps';
        }
    }
    @endphp

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <table id="backboneLogsTable" style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Backbone Link</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Type</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Old Capacity</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">New Capacity</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Execute Date</th>
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $loop->iteration }}</td>
                    <td style="padding:11px 16px;color:#111827;font-weight:500;">
                        {{ $log->backboneLink->node_a ?? 'Unknown' }} - {{ $log->backboneLink->node_b ?? 'Unknown' }}
                        <div style="font-size:0.75rem;color:#6b7280;margin-top:2px;">{{ $log->backboneLink->provider ?? '' }}</div>
                    </td>
                    <td style="padding:11px 16px;">
                        @if($log->type == 'activation')
                            <span style="background:#dcfce7;color:#166534;padding:2px 8px;border-radius:999px;font-size:.75rem;font-weight:500;">Activation</span>
                        @elseif($log->type == 'deactivation')
                            <span style="background:#fee2e2;color:#991b1b;padding:2px 8px;border-radius:999px;font-size:.75rem;font-weight:500;">Deactivation</span>
                        @elseif($log->type == 'upgrade')
                            <span style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:999px;font-size:.75rem;font-weight:500;">Upgrade</span>
                        @elseif($log->type == 'downgrade')
                            <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:999px;font-size:.75rem;font-weight:500;">Downgrade</span>
                        @endif
                    </td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ formatBw($log->old_capacity) }}</td>
                    <td style="padding:11px 16px;color:#6b7280;font-weight:600;">{{ formatBw($log->new_capacity) }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $log->execute_date ? \Carbon\Carbon::parse($log->execute_date)->format('d M Y') : '—' }}</td>
                    <td style="padding:11px 16px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                            <a href="{{ route('backbone_logs.edit', $log) }}" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;border-radius:5px;font-size:.8125rem;text-decoration:none;">Edit</a>
                            <form method="POST" action="{{ route('backbone_logs.destroy', $log) }}" onsubmit="return confirm('Delete this log entry?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:5px 10px;background:#fee2e2;color:#dc2626;border:none;border-radius:5px;font-size:.8125rem;cursor:pointer;">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper { padding: 16px; font-size: 0.875rem; }
        .dataTables_wrapper .dataTables_length select { padding-right: 30px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .dataTables_wrapper .dataTables_filter input { padding: 4px 8px; border: 1px solid #e5e7eb; border-radius: 4px; margin-left: 8px; }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#backboneLogsTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Search logs:",
                    "lengthMenu": "Show _MENU_ entries"
                },
                "columnDefs": [
                    { "orderable": false, "targets": 6 }
                ]
            });
        });
    </script>
</x-app-layout>
