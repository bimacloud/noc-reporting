<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <a href="{{ route('customers.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">{{ $customer->name }}</h1>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('customers.edit', $customer) }}" style="padding:7px 14px;background:#dbeafe;color:#1d4ed8;border-radius:6px;font-size:.875rem;text-decoration:none;">Edit</a>
            @endif
        </div>
    </x-slot>
    <div style="display:grid;grid-template-columns:1fr;gap:24px;align-items:start;@media(min-width:1024px){grid-template-columns:350px 1fr;}">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <dl style="display:grid;grid-template-columns:140px 1fr;gap:0;">
                @foreach([
                    ['Name', $customer->name],
                    ['Service', $customer->serviceType->name ?? '—'],
                    ['Provider Metro-E', $customer->provider->name ?? '—'],
                    ['Bandwidth', $customer->bandwidth],
                    ['Address', $customer->address ?: '—'],
                    ['Status', ucfirst($customer->status)],
                    ['Joined', $customer->created_at->format('d M Y')]
                ] as [$l, $v])
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">{{ $l }}</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $v }}</dd>
                @endforeach
            </dl>
        </div>

        <div style="display:flex;flex-direction:column;gap:24px;">
            <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                    <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0;">Service Logs</h3>
                </div>
                @if($serviceLogs->isEmpty())
                    <p style="font-size:.875rem;color:#6b7280;margin:0;">No service logs found for this customer.</p>
                @else
                    <div style="overflow-x:auto;">
                        <table id="serviceLogsTable" style="width:100%;border-collapse:collapse;text-align:left;">
                            <thead style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding:10px 14px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Date</th>
                                    <th style="padding:10px 14px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Type</th>
                                    <th style="padding:10px 14px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Bandwidth Change</th>
                                </tr>
                            </thead>
                            <tbody style="divide-y:1px solid #e5e7eb;">
                                @foreach($serviceLogs as $log)
                                <tr>
                                    <td style="padding:10px 14px;font-size:.875rem;color:#111827;">{{ \Carbon\Carbon::parse($log->request_date)->format('d M Y') }}</td>
                                    <td style="padding:10px 14px;font-size:.875rem;color:#111827;">
                                        <span style="padding:2px 8px;border-radius:12px;font-size:.75rem;font-weight:600;
                                            @if(in_array($log->type, ['activation', 'upgrade'])) background:#dbeafe;color:#1d4ed8;
                                            @elseif($log->type == 'downgrade') background:#f3f4f6;color:#374151;
                                            @elseif(in_array($log->type, ['suspension', 'deactivation', 'termination'])) background:#fee2e2;color:#991b1b;
                                            @endif
                                        ">{{ ucfirst($log->type) }}</span>
                                    </td>
                                    <td style="padding:10px 14px;font-size:.875rem;color:#6b7280;">
                                        @if($log->old_bandwidth || $log->new_bandwidth)
                                            {{ $log->old_bandwidth ?: '—' }} &rarr; {{ $log->new_bandwidth ?: '—' }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
                    <h3 style="font-size:1rem;font-weight:600;color:#111827;margin:0;">Customer Incidents</h3>
                </div>
                @if($incidents->isEmpty())
                    <p style="font-size:.875rem;color:#6b7280;margin:0;">No incidents recorded for this customer.</p>
                @else
                    <div style="overflow-x:auto;">
                        <table id="incidentsTable" style="width:100%;border-collapse:collapse;text-align:left;">
                            <thead style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                                <tr>
                                    <th style="padding:10px 14px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Date</th>
                                    <th style="padding:10px 14px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Duration (Mins)</th>
                                    <th style="padding:10px 14px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;">Root Cause</th>
                                </tr>
                            </thead>
                            <tbody style="divide-y:1px solid #e5e7eb;">
                                @foreach($incidents as $inc)
                                <tr>
                                    <td style="padding:10px 14px;font-size:.875rem;color:#111827;">{{ \Carbon\Carbon::parse($inc->incident_date)->format('d M Y') }}</td>
                                    <td style="padding:10px 14px;font-size:.875rem;color:#111827;">{{ $inc->duration }}</td>
                                    <td style="padding:10px 14px;font-size:.875rem;color:#6b7280;">{{ $inc->root_cause ?: '—' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper { padding: 16px; }
        .dataTables_wrapper .dataTables_length select { padding-right: 30px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .dataTables_wrapper .dataTables_filter input { padding: 4px 8px; border: 1px solid #e5e7eb; border-radius: 4px; margin-left: 8px; }
        table.dataTable thead th, table.dataTable thead td { border-bottom: 1px solid #e5e7eb !important; }
        table.dataTable.no-footer { border-bottom: none; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #2563eb; color: #fff !important; border-radius: 4px; border: 1px solid #2563eb;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#serviceLogsTable').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25],
                "order": [[0, 'desc']],
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ logs"
                }
            });
            $('#incidentsTable').DataTable({
                "pageLength": 5,
                "lengthMenu": [5, 10, 25],
                "order": [[0, 'desc']],
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ incidents"
                }
            });
        });
    </script>
</x-app-layout>
