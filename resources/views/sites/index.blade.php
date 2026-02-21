<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Sites</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Wilayah / Area dalam Site Group</p>
            </div>
            {{-- Sync button — Admin only --}}
            @if(Auth::user()->isAdmin())
            <div x-data="{ loading: false }">
                <form method="POST" action="{{ route('netbox.sync.sites') }}" @submit="loading = true">
                    @csrf
                    <button type="submit" :disabled="loading"
                        style="display:inline-flex;align-items:center;gap:8px;padding:9px 16px;background:#f59e0b;color:#fff;border:none;border-radius:8px;font-size:.875rem;font-weight:500;cursor:pointer;transition:opacity .15s;"
                        :style="loading ? 'opacity:.6;cursor:not-allowed;' : ''">
                        <template x-if="loading">
                            <svg style="animation:spin 1s linear infinite;width:15px;height:15px;" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </template>
                        <template x-if="!loading">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </template>
                        <span x-text="loading ? 'Syncing...' : 'Sync Sites'"></span>
                    </button>
                </form>
            </div>
            @endif
        </div>
        <style>@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>
    </x-slot>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.06);">

        {{-- Stats bar --}}
        <div style="display:flex;gap:0;border-bottom:1px solid #f3f4f6;">
            <div style="padding:14px 20px;border-right:1px solid #f3f4f6;">
                <p style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin:0;">Total Sites</p>
                <p style="font-size:1.25rem;font-weight:700;color:#111827;margin:0;">{{ $sites->total() }}</p>
            </div>
        </div>

        {{-- Table --}}
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Nama Site</th>
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Site Group</th>
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Region</th>
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Alamat</th>
                    <th style="text-align:center;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Lokasi</th>
                    <th style="text-align:center;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Devices</th>
                    <th style="text-align:center;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sites as $site)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background .1s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:12px 16px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="width:30px;height:30px;background:#fef3c7;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                            </span>
                            <div>
                                <p style="font-weight:600;color:#111827;margin:0;">{{ $site->name }}</p>
                                <p style="font-size:.75rem;color:#9ca3af;margin:0;">{{ $site->slug ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="padding:12px 16px;">
                        @if($site->siteGroup)
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#eef2ff;color:#6366f1;font-size:.75rem;padding:3px 8px;border-radius:9999px;font-weight:500;">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                {{ $site->siteGroup->name }}
                            </span>
                        @else
                            <span style="color:#d1d5db;">{{ $site->site_group_name ?? '-' }}</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;color:#6b7280;">{{ $site->region ?? '-' }}</td>
                    <td style="padding:12px 16px;color:#6b7280;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $site->address }}">
                        {{ $site->address ?: '-' }}
                    </td>
                    <td style="padding:12px 16px;text-align:center;">
                        @if(($site->locations_count ?? 0) > 0)
                            <a href="{{ route('locations.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;background:#ccfbf1;color:#0d9488;font-size:.75rem;font-weight:700;border-radius:50%;text-decoration:none;">{{ $site->locations_count }}</a>
                        @else
                            <span style="color:#d1d5db;">0</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;text-align:center;">
                        @if(($site->devices_count ?? 0) > 0)
                            <a href="{{ route('devices.index') }}" style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;background:#dcfce7;color:#16a34a;font-size:.75rem;font-weight:700;border-radius:50%;text-decoration:none;">{{ $site->devices_count }}</a>
                        @else
                            <span style="color:#d1d5db;">0</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;text-align:center;">
                        @php $s = $site->status ?? 'active'; @endphp
                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:.75rem;padding:3px 10px;border-radius:9999px;font-weight:500;
                            background:{{ $s === 'active' ? '#dcfce7' : '#f3f4f6' }};
                            color:{{ $s === 'active' ? '#16a34a' : '#6b7280' }};">
                            <span style="width:6px;height:6px;border-radius:50%;background:{{ $s === 'active' ? '#22c55e' : '#9ca3af' }};flex-shrink:0;"></span>
                            {{ ucfirst($s) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:40px;text-align:center;color:#9ca3af;">
                        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 8px;display:block;color:#d1d5db;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                        </svg>
                        Belum ada data. Klik <strong>Sync Sites</strong> untuk mengambil data dari NetBox.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($sites->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">
            {{ $sites->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
