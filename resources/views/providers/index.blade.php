<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Providers & NAPs</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Manage backbone network providers and NAPs</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('providers.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Provider/NAP
            </a>
            @endif
        </div>
    </x-slot>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <table id="providersTable" style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Name</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Type</th>
                    @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($providers as $provider)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $loop->iteration }}</td>
                    <td style="padding:11px 16px;font-weight:500;color:#111827;">{{ $provider->name }}</td>
                    <td style="padding:11px 16px;">
                        <span style="padding:2px 8px;border-radius:20px;font-size:.75rem;font-weight:500; {{ $provider->type == 'NAP' ? 'background:#dbeafe;color:#1e40af;' : 'background:#fef3c7;color:#92400e;' }}">
                            {{ $provider->type }}
                        </span>
                    </td>
                    @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                    <td style="padding:11px 16px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            <a href="{{ route('providers.edit', $provider) }}" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;border-radius:5px;font-size:.8125rem;text-decoration:none;">Edit</a>
                            <form method="POST" action="{{ route('providers.destroy', $provider) }}" onsubmit="return confirm('Delete this {{ $provider->type }}?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:5px 10px;background:#fee2e2;color:#dc2626;border:none;border-radius:5px;font-size:.8125rem;cursor:pointer;">Delete</button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- DataTables Script -->
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
            $('#providersTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "emptyTable": "No providers/NAPs found."
                },
                "columnDefs": [
                    @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                    { "orderable": false, "targets": 3 }
                    @endif
                ]
            });
        });
    </script>
</x-app-layout>
