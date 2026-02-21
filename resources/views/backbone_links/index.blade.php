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

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Node A</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Node B</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Provider</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Media</th>
                    <th style="padding:11px 16px;text-align:left;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Capacity</th>
                    <th style="padding:11px 16px;text-align:right;font-weight:600;color:#374151;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backboneLinks as $link)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $backboneLinks->firstItem() + $loop->index }}</td>
                    <td style="padding:11px 16px;color:#111827;font-weight:500;">{{ $link->node_a }}</td>
                    <td style="padding:11px 16px;color:#111827;font-weight:500;">{{ $link->node_b }}</td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $link->provider ?? '—' }}</td>
                    <td style="padding:11px 16px;">
                        <span style="padding:2px 8px;background:#fef3c7;color:#92400e;border-radius:20px;font-size:.75rem;font-weight:500;">{{ $link->media ?? '—' }}</span>
                    </td>
                    <td style="padding:11px 16px;color:#6b7280;">{{ $link->capacity ?? '—' }}</td>
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
                @empty
                <tr><td colspan="7" style="padding:40px 16px;text-align:center;color:#9ca3af;">No backbone links found.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($backboneLinks->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">{{ $backboneLinks->links() }}</div>
        @endif
    </div>
</x-app-layout>
