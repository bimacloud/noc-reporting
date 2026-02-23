<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Locations</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Manage all network locations</p>
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;" x-data="{ loading: false }">
                {{-- Sync Locations — Admin only --}}
                @if(Auth::user()->isAdmin())
                <form method="POST" action="{{ route('netbox.sync.locations') }}" @submit="loading = true">
                    @csrf
                    <button type="submit" :disabled="loading"
                        style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#0d9488;color:#fff;border:none;border-radius:6px;font-size:.8125rem;font-weight:500;cursor:pointer;transition:opacity .15s;"
                        :style="loading ? 'opacity:.6;cursor:not-allowed;' : ''">
                        <template x-if="loading"><svg style="animation:spin 1s linear infinite;width:14px;height:14px;" fill="none" viewBox="0 0 24 24"><circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg></template>
                        <template x-if="!loading"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></template>
                        <span x-text="loading ? 'Syncing...' : 'Sync Locations'"></span>
                    </button>
                </form>
                @endif

                {{-- Add Location button --}}
                @can('create', App\Models\Location::class)
                @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                <a href="{{ route('locations.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Location
                </a>
                @endif
                @endcan
            </div>
        </div>
        <style>
            @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        </style>
    </x-slot>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <table id="locationsTable" style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Name</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Address</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Type</th>
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locations as $location)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $loop->iteration }}</td>
                    <td style="padding:11px 16px;color:#111827;font-weight:500;">{{ $location->name }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $location->address ?? '—' }}</td>
                    <td style="padding:11px 16px;">
                        <span style="padding:2px 8px;background:#dbeafe;color:#1d4ed8;border-radius:20px;font-size:.75rem;font-weight:500;">{{ $location->type ?? '—' }}</span>
                    </td>
                    <td style="padding:11px 16px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            <a href="{{ route('locations.show', $location) }}" style="padding:5px 10px;background:#f3f4f6;color:#374151;border-radius:5px;font-size:.8125rem;text-decoration:none;">View</a>
                            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                            <a href="{{ route('locations.edit', $location) }}" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;border-radius:5px;font-size:.8125rem;text-decoration:none;">Edit</a>
                            <form method="POST" action="{{ route('locations.destroy', $location) }}" onsubmit="return confirm('Delete this location?');" style="display:inline;">
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

        </table>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper { padding: 16px; font-size: 0.875rem; }
        .dataTables_wrapper .dataTables_length select { padding-right: 30px; border: 1px solid #e5e7eb; border-radius: 4px; }
        .dataTables_wrapper .dataTables_filter input { padding: 4px 8px; border: 1px solid #e5e7eb; border-radius: 4px; margin-left: 8px; }
        table.dataTable thead th, table.dataTable thead td { border-bottom: 1px solid #e5e7eb !important; }
        table.dataTable.no-footer { border-bottom: none; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #0d9488; color: #fff !important; border-radius: 4px; border: 1px solid #0d9488;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#locationsTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "emptyTable": "No locations found."
                },
                "columnDefs": [
                    { "orderable": false, "targets": 4 } /* Disable sorting on Actions column */
                ]
            });
        });
    </script>
</x-app-layout>
