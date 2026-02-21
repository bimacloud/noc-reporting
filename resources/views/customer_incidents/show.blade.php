<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <a href="{{ route('customer_incidents.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Incident — {{ $incident->customer->name ?? '—' }}</h1>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('customer_incidents.edit', $incident) }}" style="padding:7px 14px;background:#dbeafe;color:#1d4ed8;border-radius:6px;font-size:.875rem;text-decoration:none;">Edit</a>
            @endif
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <dl style="display:grid;grid-template-columns:160px 1fr;gap:0;">
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Customer</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $incident->customer->name ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Date</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('d M Y H:i') : '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Status</dt>
                <dd style="padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">
                    @if($incident->status === 'open')
                        <span style="padding:2px 8px;background:#fee2e2;color:#991b1b;border-radius:12px;font-size:.75rem;font-weight:600;">Open</span>
                    @else
                        <span style="padding:2px 8px;background:#d1fae5;color:#065f46;border-radius:12px;font-size:.75rem;font-weight:600;">Closed</span>
                    @endif
                </dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Duration</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $incident->duration ?? 0 }} minutes</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Root Cause</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $incident->root_cause ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;">Description</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;margin:0;">{{ $incident->description ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
