<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Devices</h1>
                <p style="font-size:.875rem;color:#6b7280;margin:4px 0 0;">Manage network hardware assets.</p>
            </div>
            <div style="display:flex;align-items:center;gap:8px;" x-data="{ loading: false }">
                {{-- Sync Devices — Admin only --}}
                @if(Auth::user()->isAdmin())
                <form method="POST" action="{{ route('netbox.sync.devices') }}" @submit="loading = true">
                    @csrf
                    <button type="submit" :disabled="loading"
                        style="display:inline-flex;align-items:center;gap:6px;padding:9px 16px;background:#059669;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;transition:opacity .15s;"
                        :style="loading ? 'opacity:.6;cursor:not-allowed;' : ''">
                        <template x-if="loading">
                            <svg style="animation:spin 1s linear infinite;width:15px;height:15px;" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <template x-if="!loading">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Syncing...' : 'Sync Devices'"></span>
                    </button>
                </form>
                @endif

                {{-- Add Device --}}
                @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                <a href="{{ route('devices.create') }}" style="padding:9px 16px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Device
                </a>
                @endif
            </div>
        </div>
        <style>@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>
    </x-slot>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <tr>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Name</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Type</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Details</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Location</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Status</th>
                        <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Last Updated</th>
                        <th style="padding:12px 16px;width:100px;"></th>
                    </tr>
                </thead>
                <tbody style="divide-y:1px solid #e5e7eb;">
                    @forelse($devices as $device)
                    <tr style="hover:bg-slate-50">
                        <td style="padding:10px 16px;">
                            <a href="{{ route('devices.show', $device) }}" style="color:#2563eb;font-weight:500;text-decoration:none;">{{ $device->name }}</a>
                        </td>
                        <td style="padding:10px 16px;font-size:.875rem;color:#111827;">{{ $device->deviceType->name ?? '—' }}</td>
                        <td style="padding:10px 16px;font-size:.75rem;color:#6b7280;">
                            {{ $device->brand }} {{ $device->model }}<br>SN: {{ $device->serial_number ?? 'N/A' }}
                        </td>
                        <td style="padding:10px 16px;font-size:.875rem;color:#111827;">{{ $device->location->name ?? 'Unknown' }}</td>
                        <td style="padding:10px 16px;">
                            @if($device->status === 'active')
                                <span style="padding:2px 8px;background:#dcfce7;color:#166534;border-radius:12px;font-size:.75rem;font-weight:600;">Active</span>
                            @else
                                <span style="padding:2px 8px;background:#f3f4f6;color:#374151;border-radius:12px;font-size:.75rem;font-weight:600;">Inactive</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;font-size:.75rem;color:#6b7280;">{{ $device->updated_at->diffForHumans() }}</td>
                        <td style="padding:10px 16px;text-align:right;">
                            <div style="display:flex;justify-content:flex-end;gap:8px;">
                                <a href="{{ route('devices.show', $device) }}" style="color:#6b7280;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                                @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                                <a href="{{ route('devices.edit', $device) }}" style="color:#2563eb;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                                <form action="{{ route('devices.destroy', $device) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:none;padding:0;color:#ef4444;cursor:pointer;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:24px;text-align:center;color:#6b7280;font-size:.875rem;">No devices found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid #e5e7eb;">
            {{ $devices->links() }}
        </div>
    </div>
</x-app-layout>
