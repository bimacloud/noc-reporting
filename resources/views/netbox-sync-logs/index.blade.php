<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">NetBox Sync Log</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Riwayat sinkronisasi data dari NetBox — per baris data</p>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <a href="{{ route('netbox.sync.logs') }}" style="padding:7px 14px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.8125rem;font-weight:500;text-decoration:none;border:1px solid #e5e7eb;">🔄 Refresh</a>
                <form method="POST" action="{{ route('netbox.sync.logs.clear') }}" onsubmit="return confirm('Hapus semua log?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="padding:7px 14px;background:#fee2e2;color:#991b1b;border-radius:6px;font-size:.8125rem;font-weight:500;border:none;cursor:pointer;">🗑 Hapus Log</button>
                </form>
            </div>
        </div>
    </x-slot>

    {{-- Stats Cards --}}
    @php
        $entityLabels = ['site_groups'=>'Site Groups','sites'=>'Sites','locations'=>'Locations','devices'=>'Devices'];
        $entityColors = ['site_groups'=>'#6366f1','sites'=>'#f59e0b','locations'=>'#0d9488','devices'=>'#059669'];
    @endphp
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-bottom:16px;">
        @foreach($entityLabels as $key => $label)
        @php
            $rows = $stats->get($key, collect());
            $okCount     = $rows->firstWhere('status','ok')?->total ?? 0;
            $skipCount   = $rows->firstWhere('status','skipped')?->total ?? 0;
            $errCount    = $rows->firstWhere('status','error')?->total ?? 0;
            $totalCount  = $okCount + $skipCount + $errCount;
        @endphp
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:14px 16px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $entityColors[$key] }};flex-shrink:0;"></span>
                <span style="font-size:.8125rem;font-weight:600;color:#374151;">{{ $label }}</span>
            </div>
            <div style="display:flex;gap:10px;font-size:.75rem;">
                <span style="color:#16a34a;font-weight:600;">✓ {{ $okCount }}</span>
                @if($skipCount)<span style="color:#d97706;font-weight:600;">~ {{ $skipCount }}</span>@endif
                @if($errCount)<span style="color:#dc2626;font-weight:600;">✗ {{ $errCount }}</span>@endif
                @if($totalCount === 0)<span style="color:#9ca3af;">Belum ada log</span>@endif
            </div>
        </div>
        @endforeach
    </div>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">

        {{-- Filter bar --}}
        <form method="GET" action="{{ route('netbox.sync.logs') }}"
              style="display:flex;gap:10px;align-items:center;padding:12px 16px;border-bottom:1px solid #f3f4f6;flex-wrap:wrap;background:#f9fafb;">
            <select name="type" style="padding:7px 10px;border:1px solid #e5e7eb;border-radius:6px;font-size:.8125rem;background:#fff;color:#374151;">
                <option value="">Semua Entity</option>
                <option value="site_groups"  @selected($type==='site_groups')>Site Groups</option>
                <option value="sites"        @selected($type==='sites')>Sites</option>
                <option value="locations"    @selected($type==='locations')>Locations</option>
                <option value="devices"      @selected($type==='devices')>Devices</option>
            </select>
            <select name="status" style="padding:7px 10px;border:1px solid #e5e7eb;border-radius:6px;font-size:.8125rem;background:#fff;color:#374151;">
                <option value="">Semua Status</option>
                <option value="ok"      @selected($status==='ok')>✓ OK</option>
                <option value="skipped" @selected($status==='skipped')>~ Partial</option>
                <option value="error"   @selected($status==='error')>✗ Error</option>
            </select>
            <button type="submit" style="padding:7px 14px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.8125rem;font-weight:500;cursor:pointer;">Filter</button>
            @if($type || $status)
            <a href="{{ route('netbox.sync.logs') }}" style="padding:7px 12px;background:#f3f4f6;color:#6b7280;border-radius:6px;font-size:.8125rem;text-decoration:none;border:1px solid #e5e7eb;">✕ Reset</a>
            @endif
            <span style="margin-left:auto;font-size:.75rem;color:#9ca3af;">{{ $logs->count() }} baris</span>
        </form>

        {{-- Table --}}
        <div style="overflow-x:auto;">
        <table id="syncLogsTable" style="width:100%;border-collapse:collapse;font-size:.8125rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="text-align:left;padding:9px 16px;font-weight:600;color:#6b7280;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">Waktu</th>
                    <th style="text-align:left;padding:9px 16px;font-weight:600;color:#6b7280;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;">Entity</th>
                    <th style="text-align:left;padding:9px 16px;font-weight:600;color:#6b7280;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;">Nama</th>
                    <th style="text-align:center;padding:9px 16px;font-weight:600;color:#6b7280;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                    <th style="text-align:left;padding:9px 16px;font-weight:600;color:#6b7280;font-size:.7rem;text-transform:uppercase;letter-spacing:.05em;">Pesan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr style="border-bottom:1px solid #f3f4f6;{{ $log->status === 'error' ? 'background:#fff9f9;' : '' }}transition:background .1s;"
                    onmouseover="this.style.background='{{ $log->status === 'error' ? '#fff1f1' : '#f9fafb' }}'"
                    onmouseout="this.style.background='{{ $log->status === 'error' ? '#fff9f9' : '' }}'">
                    <td style="padding:9px 16px;color:#9ca3af;white-space:nowrap;font-size:.75rem;">
                        {{ $log->synced_at->format('d/m H:i:s') }}
                    </td>
                    <td style="padding:9px 16px;">
                        @php $ec = $entityColors[$log->entity_type] ?? '#6b7280'; @endphp
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:.7rem;padding:2px 8px;border-radius:9999px;font-weight:600;background:{{ $ec }}18;color:{{ $ec }};">
                            {{ $entityLabels[$log->entity_type] ?? $log->entity_type }}
                        </span>
                    </td>
                    <td style="padding:9px 16px;font-weight:500;color:#111827;">
                        {{ $log->entity_name ?? '-' }}
                        @if($log->netbox_id)
                            <span style="font-size:.7rem;color:#9ca3af;font-weight:400;"> #{{ $log->netbox_id }}</span>
                        @endif
                    </td>
                    <td style="padding:9px 16px;text-align:center;">
                        @if($log->status === 'ok')
                            <span style="display:inline-flex;align-items:center;gap:3px;background:#dcfce7;color:#16a34a;font-size:.7rem;padding:2px 8px;border-radius:9999px;font-weight:600;">✓ OK</span>
                        @elseif($log->status === 'skipped')
                            <span style="display:inline-flex;align-items:center;gap:3px;background:#fef9c3;color:#a16207;font-size:.7rem;padding:2px 8px;border-radius:9999px;font-weight:600;">~ Partial</span>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:3px;background:#fee2e2;color:#dc2626;font-size:.7rem;padding:2px 8px;border-radius:9999px;font-weight:600;">✗ Error</span>
                        @endif
                    </td>
                    <td style="padding:9px 16px;color:{{ $log->status === 'error' ? '#dc2626' : '#6b7280' }};max-width:380px;">
                        @if($log->status === 'error')
                            <details>
                                <summary style="cursor:pointer;font-size:.75rem;color:#dc2626;">{{ Str::limit($log->message, 80) }}</summary>
                                <pre style="margin:6px 0 0;padding:8px;background:#fef2f2;border-radius:4px;font-size:.7rem;color:#b91c1c;overflow-x:auto;white-space:pre-wrap;word-break:break-all;">{{ $log->message }}</pre>
                                @if($log->payload)
                                <pre style="margin:4px 0 0;padding:8px;background:#f9fafb;border-radius:4px;font-size:.65rem;color:#6b7280;overflow-x:auto;max-height:150px;white-space:pre-wrap;">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </details>
                        @else
                            <span style="font-size:.75rem;">{{ $log->message }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper { padding: 16px; font-size: 0.875rem; }
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
            $('#syncLogsTable').DataTable({
                "pageLength": 10,
                "order": [[0, "desc"]], /* Default sort by Waktu Descending */
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "emptyTable": "Belum ada log. Jalankan sync dari menu terkait."
                }
            });
        });
    </script>
</x-app-layout>
