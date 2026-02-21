<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Site Groups</h1>
                <p style="font-size:.8125rem;color:#6b7280;margin:0;">Daftar Datacenter &amp; POP dari NetBox</p>
            </div>
            {{-- Sync button — Admin only --}}
            @if(Auth::user()->isAdmin())
            <div x-data="{ loading: false }">
                <form method="POST" action="{{ route('netbox.sync.site-groups') }}" @submit="loading = true">
                    @csrf
                    <button type="submit" :disabled="loading"
                        style="display:inline-flex;align-items:center;gap:8px;padding:9px 16px;background:#6366f1;color:#fff;border:none;border-radius:8px;font-size:.875rem;font-weight:500;cursor:pointer;transition:opacity .15s;"
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
                        <span x-text="loading ? 'Syncing...' : 'Sync Site Groups'"></span>
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
                <p style="font-size:.7rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.05em;margin:0;">Total Site Groups</p>
                <p style="font-size:1.25rem;font-weight:700;color:#111827;margin:0;">{{ $siteGroups->total() }}</p>
            </div>
        </div>

        {{-- Table --}}
        <table style="width:100%;border-collapse:collapse;font-size:.875rem;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Nama</th>
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Slug</th>
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Parent</th>
                    <th style="text-align:left;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Deskripsi</th>
                    <th style="text-align:center;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Sites</th>
                    <th style="text-align:center;padding:10px 16px;font-weight:600;color:#6b7280;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">Depth</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siteGroups as $sg)
                <tr style="border-bottom:1px solid #f3f4f6;transition:background .1s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                    <td style="padding:12px 16px;">
                        <div style="display:flex;align-items:center;gap:12px;padding-left:{{ $sg->depth * 28 }}px;">
                            @if($sg->depth > 0)
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d1d5db" stroke-width="2" style="margin-right:-6px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            @endif
                            <span style="width:30px;height:30px;background:#eef2ff;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </span>
                            <span style="font-weight:600;color:#111827;">{{ $sg->name }}</span>
                        </div>
                    </td>
                    <td style="padding:12px 16px;">
                        <code style="font-size:.75rem;background:#f3f4f6;padding:2px 6px;border-radius:4px;color:#6b7280;">{{ $sg->slug ?? '-' }}</code>
                    </td>
                    <td style="padding:12px 16px;">
                        @if($sg->parent_name)
                            <span style="display:inline-flex;align-items:center;padding:4px 10px;background:#f3f4f6;color:#4b5563;border-radius:9999px;font-size:.75rem;font-weight:500;white-space:nowrap;">
                                {{ $sg->parent_name }}
                            </span>
                        @else
                            <span style="color:#d1d5db;font-weight:500;">-</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;color:#6b7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        {{ $sg->description ?: '-' }}
                    </td>
                    <td style="padding:12px 16px;text-align:center;">
                        @if($sg->sites_count > 0)
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;background:#dbeafe;color:#1d4ed8;font-size:.75rem;font-weight:700;border-radius:50%;">{{ $sg->sites_count }}</span>
                        @else
                            <span style="color:#d1d5db;">0</span>
                        @endif
                    </td>
                    <td style="padding:12px 16px;text-align:center;">
                        <span style="font-size:.75rem;background:#f3f4f6;padding:2px 8px;border-radius:9999px;color:#6b7280;">{{ $sg->depth }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:40px;text-align:center;color:#9ca3af;">
                        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 8px;display:block;color:#d1d5db;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Belum ada data. Klik <strong>Sync Site Groups</strong> untuk mengambil data dari NetBox.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($siteGroups->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #f3f4f6;">
            {{ $siteGroups->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
