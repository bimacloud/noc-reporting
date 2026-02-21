<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Device Monthly Reports</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Track monthly device health status</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('device_reports.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Report
            </a>
            @endif
        </div>
    </x-slot>

    {{-- Filter --}}
    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:14px 16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
        <form method="GET" action="{{ route('device_reports.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label style="display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Device</label>
                <select name="device_id" style="border:1px solid #d1d5db;border-radius:6px;padding:6px 10px;font-size:.875rem;color:#374151;">
                    <option value="">All Devices</option>
                    @foreach($devices as $dev)
                        <option value="{{ $dev->id }}" {{ request('device_id') == $dev->id ? 'selected' : '' }}>{{ $dev->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Month</label>
                <input type="month" name="month" value="{{ request('month') }}" style="border:1px solid #d1d5db;border-radius:6px;padding:6px 10px;font-size:.875rem;color:#374151;">
            </div>
            <button type="submit" style="padding:7px 14px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;cursor:pointer;">Filter</button>
            <a href="{{ route('device_reports.index') }}" style="padding:7px 14px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Reset</a>
        </form>
    </div>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Device</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Month</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Physical</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">PSU</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Fan</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Downtime</th>
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $reports->firstItem() + $loop->index }}</td>
                    <td style="padding:11px 16px;color:#111827;font-weight:500;">{{ $report->device->name ?? '—' }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $report->month ? \Carbon\Carbon::parse($report->month)->format('M Y') : '—' }}</td>
                    <td style="padding:11px 16px;">
                        @php $s = $report->physical_status; @endphp
                        <span style="padding:2px 8px;background:{{ $s === 'good' ? '#d1fae5' : '#fee2e2' }};color:{{ $s === 'good' ? '#065f46' : '#991b1b' }};border-radius:20px;font-size:.75rem;font-weight:500;text-transform:capitalize;">{{ $s }}</span>
                    </td>
                    <td style="padding:11px 16px;">
                        @php $s = $report->psu_status; @endphp
                        <span style="padding:2px 8px;background:{{ $s === 'good' ? '#d1fae5' : '#fee2e2' }};color:{{ $s === 'good' ? '#065f46' : '#991b1b' }};border-radius:20px;font-size:.75rem;font-weight:500;text-transform:capitalize;">{{ $s }}</span>
                    </td>
                    <td style="padding:11px 16px;">
                        @php $s = $report->fan_status; @endphp
                        <span style="padding:2px 8px;background:{{ $s === 'good' ? '#d1fae5' : '#fee2e2' }};color:{{ $s === 'good' ? '#065f46' : '#991b1b' }};border-radius:20px;font-size:.75rem;font-weight:500;text-transform:capitalize;">{{ $s }}</span>
                    </td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $report->duration_downtime ?? 0 }} min</td>
                    <td style="padding:11px 16px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            <a href="{{ route('device_reports.show', $report) }}" style="padding:5px 10px;background:#f3f4f6;color:#374151;border-radius:5px;font-size:.8125rem;text-decoration:none;">View</a>
                            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                            <a href="{{ route('device_reports.edit', $report) }}" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;border-radius:5px;font-size:.8125rem;text-decoration:none;">Edit</a>
                            <form method="POST" action="{{ route('device_reports.destroy', $report) }}" onsubmit="return confirm('Delete this report?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:5px 10px;background:#fee2e2;color:#dc2626;border:none;border-radius:5px;font-size:.8125rem;cursor:pointer;">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="padding:40px 16px;text-align:center;color:#9ca3af;">No device reports found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reports->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">{{ $reports->links() }}</div>
        @endif
    </div>
</x-app-layout>
