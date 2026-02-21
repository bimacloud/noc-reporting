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
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
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
                @forelse($locations as $location)
                <tr style="border-bottom:1px solid #f3f4f6;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:11px 16px;color:#9ca3af;">{{ $locations->firstItem() + $loop->index }}</td>
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
                @empty
                <tr>
                    <td colspan="5" style="padding:40px 16px;text-align:center;color:#9ca3af;font-size:.875rem;">
                        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" style="margin:0 auto 8px;display:block;color:#d1d5db;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        No locations found.
                        @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                        <a href="{{ route('locations.create') }}" style="color:#2563eb;text-decoration:none;">Add the first one</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($locations->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">
            {{ $locations->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
