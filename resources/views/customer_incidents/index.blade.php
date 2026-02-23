<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Customer Incidents</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Log and track customer service incidents</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('customer_incidents.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#dc2626;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Log Incident
            </a>
            @endif
        </div>
    </x-slot>

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

    {{-- Filter --}}
    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:14px 16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.04);">
        <form method="GET" action="{{ route('customer_incidents.index') }}" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap;">
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
                <label style="display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:4px;">Month</label>
                <input type="month" name="month" value="{{ request('month') }}" style="border:1px solid #d1d5db;border-radius:6px;padding:6px 10px;font-size:.875rem;color:#374151;">
            </div>
            <button type="submit" style="padding:7px 14px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;cursor:pointer;">Filter</button>
            <a href="{{ route('customer_incidents.index') }}" style="padding:7px 14px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Reset</a>
        </form>
    </div>

    {{-- Total downtime stat --}}
    @if($totalDowntime > 0)
    <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span style="font-size:.875rem;color:#991b1b;font-weight:500;">Total Downtime: <strong>{{ number_format($totalDowntime / 60, 1) }} hours ({{ $totalDowntime }} minutes)</strong></span>
    </div>
    @endif

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Customer</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Date</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Duration</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Root Cause</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incidents as $incident)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $incidents->firstItem() + $loop->index }}</td>
                    <td style="padding:11px 16px;color:#111827;font-weight:500;">{{ $incident->customer->name ?? '—' }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('d M Y H:i') : '—' }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $incident->duration ?? 0 }} min</td>
                    <td style="padding:11px 16px;color:#6b7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $incident->root_cause ?? '—' }}</td>
                    <td style="padding:11px 16px;">
                        @if($incident->status === 'open')
                            <span style="padding:2px 8px;background:#fee2e2;color:#991b1b;border-radius:20px;font-size:.75rem;font-weight:500;">Open</span>
                        @else
                            <span style="padding:2px 8px;background:#d1fae5;color:#065f46;border-radius:20px;font-size:.75rem;font-weight:500;">Closed</span>
                        @endif
                    </td>
                    <td style="padding:11px 16px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            <a href="{{ route('customer_incidents.show', $incident) }}" style="padding:5px 10px;background:#f3f4f6;color:#374151;border-radius:5px;font-size:.8125rem;text-decoration:none;">View</a>
                            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                            @if($incident->status === 'open')
                            <a href="{{ route('customer_incidents.resolve_form', $incident) }}" style="padding:5px 10px;background:#10b981;color:#fff;border-radius:5px;font-size:.8125rem;text-decoration:none;">Resolve</a>
                            @endif
                            <a href="{{ route('customer_incidents.edit', $incident) }}" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;border-radius:5px;font-size:.8125rem;text-decoration:none;">Edit</a>
                            <form method="POST" action="{{ route('customer_incidents.destroy', $incident) }}" onsubmit="return confirm('Delete this incident?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:5px 10px;background:#fee2e2;color:#dc2626;border:none;border-radius:5px;font-size:.8125rem;cursor:pointer;">Delete</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:40px 16px;text-align:center;color:#9ca3af;">
                        No incidents found. 
                        @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                        <a href="{{ route('customer_incidents.create') }}" style="color:#2563eb;text-decoration:none;">Log an incident</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($incidents->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">{{ $incidents->links() }}</div>
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
