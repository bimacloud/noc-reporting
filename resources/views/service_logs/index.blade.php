<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Service Logs</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Activation & upgrade service history</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <div style="display:flex;gap:10px;align-items:center;">
                <form action="{{ route('service_logs.import') }}" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
                    @csrf
                    <input type="file" name="csv_file" id="csvFile" accept=".csv" onchange="document.getElementById('importForm').submit()">
                </form>
                <a href="{{ route('service_logs.template') }}" style="padding:7px 12px;color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;font-size:.75rem;font-weight:500;text-decoration:none;" title="Download CSV Template">
                    CSV Template
                </a>
                <button type="button" onclick="document.getElementById('csvFile').click()" style="padding:9px 16px;background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> Import CSV
                </button>
                <a href="{{ route('service_logs.create') }}" style="padding:9px 16px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Add Service Log
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    @if(session('import_errors'))
    <div style="margin-bottom:20px;background:#fee2e2;border:1px solid #ef4444;color:#991b1b;padding:12px 16px;border-radius:10px;">
        <p style="font-weight:600;margin:0 0 8px;font-size:0.875rem;">Import Errors:</p>
        <ul style="margin:0;padding-left:20px;font-size:0.75rem;">
            @foreach(session('import_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 33px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 31px !important;
            right: 4px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151 !important;
            font-size: .875rem !important;
            padding-left: 0 !important;
        }
    </style>

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

    {{-- Filter --}}
    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:14px 16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
        <form method="GET" action="{{ route('service_logs.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label style="display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Customer</label>
                <select name="customer_id" id="filter_customer_id" style="border:1px solid #d1d5db;border-radius:6px;padding:6px 10px;font-size:.875rem;color:#374151;min-width:180px;">
                    <option value="">All Customers</option>
                    @foreach($customers as $cust)
                        <option value="{{ $cust->id }}" {{ request('customer_id') == $cust->id ? 'selected' : '' }}>{{ $cust->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Type</label>
                <select name="type" style="border:1px solid #d1d5db;border-radius:6px;padding:6px 10px;font-size:.875rem;color:#374151;">
                    <option value="">All Types</option>
                    <option value="activation" {{ request('type') === 'activation' ? 'selected' : '' }}>Activation</option>
                    <option value="upgrade" {{ request('type') === 'upgrade' ? 'selected' : '' }}>Upgrade</option>
                    <option value="downgrade" {{ request('type') === 'downgrade' ? 'selected' : '' }}>Downgrade</option>
                    <option value="suspension" {{ request('type') === 'suspension' ? 'selected' : '' }}>Suspension</option>
                    <option value="termination" {{ request('type') === 'termination' ? 'selected' : '' }}>Termination</option>
                </select>
            </div>
            <button type="submit" style="padding:7px 14px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;cursor:pointer;">Filter</button>
            <a href="{{ route('service_logs.index') }}" style="padding:7px 14px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Reset</a>
        </form>
    </div>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Customer</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Type</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Old BW</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">New BW</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Request Date</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Execute Date</th>
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $logs->firstItem() + $loop->index }}</td>
                    <td style="padding:11px 16px;color:#111827;font-weight:500;">{{ $log->customer->name ?? '—' }}</td>
                    <td style="padding:11px 16px;">
                        @php
                            $typeColors = [
                                'activation' => ['#d1fae5', '#065f46'],
                                'upgrade' => ['#dbeafe', '#1d4ed8'],
                                'downgrade' => ['#fef3c7', '#92400e'],
                                'suspension' => ['#fee2e2', '#991b1b'],
                                'termination' => ['#f3f4f6', '#374151'],
                            ];
                            $c = $typeColors[$log->type] ?? ['#f3f4f6', '#374151'];
                        @endphp
                        <span style="padding:2px 8px;background:{{ $c[0] }};color:{{ $c[1] }};border-radius:20px;font-size:.75rem;font-weight:500;text-transform:capitalize;">{{ $log->type }}</span>
                    </td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ formatBw($log->old_bandwidth) }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ formatBw($log->new_bandwidth) }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $log->request_date ? \Carbon\Carbon::parse($log->request_date)->format('d M Y') : '—' }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $log->execute_date ? \Carbon\Carbon::parse($log->execute_date)->format('d M Y') : '—' }}</td>
                    <td style="padding:11px 16px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            <a href="{{ route('service_logs.show', $log) }}" style="padding:5px 10px;background:#f3f4f6;color:#374151;border-radius:5px;font-size:.8125rem;text-decoration:none;">View</a>
                            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                            <a href="{{ route('service_logs.edit', $log) }}" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;border-radius:5px;font-size:.8125rem;text-decoration:none;">Edit</a>
                            <form method="POST" action="{{ route('service_logs.destroy', $log) }}" onsubmit="return confirm('Delete this log?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:5px 10px;background:#fee2e2;color:#dc2626;border:none;border-radius:5px;font-size:.8125rem;cursor:pointer;">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="padding:40px 16px;text-align:center;color:#9ca3af;">No service logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($logs->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">{{ $logs->links() }}</div>
        @endif
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#filter_customer_id').select2({
                placeholder: 'All Customers',
                width: '100%'
            });
        });
    </script>
    @endpush
</x-app-layout>
