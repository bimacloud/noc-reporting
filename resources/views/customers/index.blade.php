<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Customers</h1>
                <p style="font-size:.875rem;color:#6b7280;margin:4px 0 0;">Manage internet service subscribers.</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <div style="display:flex;gap:10px;align-items:center;">
                <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" id="importForm" style="display:none;">
                    @csrf
                    <input type="file" name="csv_file" id="csvFile" accept=".csv" onchange="document.getElementById('importForm').submit()">
                </form>
                <a href="{{ route('customers.template') }}" style="padding:7px 12px;color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;font-size:.75rem;font-weight:500;text-decoration:none;" title="Download CSV Template">
                    CSV Template
                </a>
                <button type="button" onclick="document.getElementById('csvFile').click()" style="padding:9px 16px;background:#f3f4f6;color:#374151;border:1px solid #d1d5db;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg> Import CSV
                </button>
                <a href="{{ route('customers.create') }}" style="padding:9px 16px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Add Customer
                </a>
            </div>
            @endif
        </div>
    </x-slot>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <tr>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Name</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Service/Name</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Bandwidth</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Address</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Status</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Registered</th>
                        <th style="padding:12px 16px;width:100px;"></th>
                    </tr>
                </thead>
                <tbody style="divide-y:1px solid #e5e7eb;">
                    @forelse($customers as $customer)
                    <tr style="hover:bg-slate-50">
                        <td style="padding:10px 16px;">
                            <a href="{{ route('customers.show', $customer) }}" style="color:#2563eb;font-weight:500;text-decoration:none;">{{ $customer->name }}</a>
                        </td>
                        <td style="padding:10px 16px;font-size:.875rem;color:#111827;">{{ $customer->serviceType->name ?? '—' }}</td>
                        <td style="padding:10px 16px;font-size:.875rem;color:#111827;">{{ $customer->bandwidth }}</td>
                        <td style="padding:10px 16px;font-size:.875rem;color:#111827;">{{ Str::limit($customer->address, 30) }}</td>
                        <td style="padding:10px 16px;">
                            @if($customer->status === 'active')
                                <span style="padding:2px 8px;background:#dcfce7;color:#166534;border-radius:12px;font-size:.75rem;font-weight:600;">Active</span>
                            @elseif($customer->status === 'suspended')
                                <span style="padding:2px 8px;background:#fee2e2;color:#991b1b;border-radius:12px;font-size:.75rem;font-weight:600;">Suspended</span>
                            @else
                                <span style="padding:2px 8px;background:#f3f4f6;color:#374151;border-radius:12px;font-size:.75rem;font-weight:600;">Inactive</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;font-size:.75rem;color:#6b7280;">
                            {{ $customer->registration_date ? \Carbon\Carbon::parse($customer->registration_date)->format('d M Y') : $customer->created_at->format('d M Y') }}
                        </td>
                        <td style="padding:10px 16px;text-align:right;">
                            <div style="display:flex;justify-content:flex-end;gap:8px;">
                                <a href="{{ route('customers.show', $customer) }}" style="color:#6b7280;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                                <a href="{{ route('customers.edit', $customer) }}" style="color:#2563eb;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:none;padding:0;color:#ef4444;cursor:pointer;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding:24px;text-align:center;color:#6b7280;font-size:.875rem;">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid #e5e7eb;">
            {{ $customers->links() }}
        </div>
    </div>
</x-app-layout>
