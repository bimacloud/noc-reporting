<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Backbone Links</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Manage backbone network links</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('backbone_links.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Link
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
        <table id="backboneLinksTable" style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Node A</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Node B</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Node C</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Node D</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Node E</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Provider</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Media</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Capacity</th>
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($backboneLinks as $link)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $loop->iteration }}</td>
                    <td style="padding:11px 16px;">
                        <span style="color:#111827;font-weight:500;display:block;">{{ $link->node_a }}</span>
                        <span style="font-size:0.75rem;color:#6b7280;display:block;margin-top:2px;">{{ $link->site_group_a ?? '—' }}</span>
                    </td>
                    <td style="padding:11px 16px;">
                        <span style="color:#111827;font-weight:500;display:block;">{{ $link->node_b }}</span>
                        <span style="font-size:0.75rem;color:#6b7280;display:block;margin-top:2px;">{{ $link->site_group_b ?? '—' }}</span>
                    </td>
                    <td style="padding:11px 16px;">
                        @if($link->node_c)
                        <span style="color:#111827;font-weight:500;display:block;">{{ $link->node_c }}</span>
                        <span style="font-size:0.75rem;color:#6b7280;display:block;margin-top:2px;">{{ $link->site_group_c ?? '—' }}</span>
                        @else
                        <span style="color:#9ca3af;font-style:italic;">—</span>
                        @endif
                    </td>
                    <td style="padding:11px 16px;">
                        @if($link->node_d)
                        <span style="color:#111827;font-weight:500;display:block;">{{ $link->node_d }}</span>
                        <span style="font-size:0.75rem;color:#6b7280;display:block;margin-top:2px;">{{ $link->site_group_d ?? '—' }}</span>
                        @else
                        <span style="color:#9ca3af;font-style:italic;">—</span>
                        @endif
                    </td>
                    <td style="padding:11px 16px;">
                        @if($link->node_e)
                        <span style="color:#111827;font-weight:500;display:block;">{{ $link->node_e }}</span>
                        <span style="font-size:0.75rem;color:#6b7280;display:block;margin-top:2px;">{{ $link->site_group_e ?? '—' }}</span>
                        @else
                        <span style="color:#9ca3af;font-style:italic;">—</span>
                        @endif
                    </td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $link->provider ?? '—' }}</td>
                    <td style="padding:11px 16px;">
                        <span style="padding:2px 8px;background:#fef3c7;color:#92400e;border-radius:20px;font-size:.75rem;font-weight:500;">{{ $link->media ?? '—' }}</span>
                    </td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ formatBw($link->capacity) }}</td>
                    <td style="padding:11px 16px;text-align:right;">
                        <div style="display:inline-flex;gap:6px;">
                            <a href="{{ route('backbone_links.show', $link) }}" style="padding:5px 10px;background:#f3f4f6;color:#374151;border-radius:5px;font-size:.8125rem;text-decoration:none;">View</a>
                            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                            <a href="{{ route('backbone_links.edit', $link) }}" style="padding:5px 10px;background:#dbeafe;color:#1d4ed8;border-radius:5px;font-size:.8125rem;text-decoration:none;">Edit</a>
                            <form method="POST" action="{{ route('backbone_links.destroy', $link) }}" onsubmit="return confirm('Delete this link?');" style="display:inline;">
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
            $('#backboneLinksTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries",
                    "emptyTable": "No backbone links found."
                },
                "columnDefs": [
                    { "orderable": false, "targets": 9 }
                ]
            });
        });
    </script>
</x-app-layout>
