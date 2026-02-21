<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <a href="{{ route('locations.index') }}" style="color:#6b7280;text-decoration:none;display:flex;align-items:center;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">{{ $location->name }}</h1>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('locations.edit', $location) }}" style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;background:#dbeafe;color:#1d4ed8;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;">Edit</a>
            @endif
        </div>
    </x-slot>

    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <dl style="display:grid;grid-template-columns:160px 1fr;gap:12px 0;">
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:8px 0;border-bottom:1px solid #f3f4f6;">Name</dt>
                <dd style="font-size:.875rem;color:#111827;padding:8px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $location->name }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:8px 0;border-bottom:1px solid #f3f4f6;">Address</dt>
                <dd style="font-size:.875rem;color:#111827;padding:8px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $location->address ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:8px 0;border-bottom:1px solid #f3f4f6;">Type</dt>
                <dd style="font-size:.875rem;color:#111827;padding:8px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $location->type ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:8px 0;">Created</dt>
                <dd style="font-size:.875rem;color:#111827;padding:8px 0;margin:0;">{{ $location->created_at->format('d M Y H:i') }}</dd>
            </dl>
        </div>

        @if($location->devices->count())
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:16px 24px;margin-top:16px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <h3 style="font-size:.875rem;font-weight:600;color:#374151;margin:0 0 12px;">Devices at this location</h3>
            @foreach($location->devices as $device)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f3f4f6;">
                <span style="font-size:.875rem;color:#111827;">{{ $device->name }}</span>
                <span style="font-size:.75rem;color:#9ca3af;">{{ $device->type }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-app-layout>
