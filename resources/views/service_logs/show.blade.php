<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <a href="{{ route('service_logs.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Service Log — {{ $log->customer->name ?? '—' }}</h1>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('service_logs.edit', $log) }}" style="padding:7px 14px;background:#dbeafe;color:#1d4ed8;border-radius:6px;font-size:.875rem;text-decoration:none;">Edit</a>
            @endif
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <dl style="display:grid;grid-template-columns:160px 1fr;gap:0;">
                @php
                    $typeColors = ['activation'=>['#d1fae5','#065f46'],'upgrade'=>['#dbeafe','#1d4ed8'],'downgrade'=>['#fef3c7','#92400e'],'suspension'=>['#fee2e2','#991b1b'],'termination'=>['#f3f4f6','#374151']];
                    $c = $typeColors[$log->type] ?? ['#f3f4f6','#374151'];
                @endphp
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Customer</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $log->customer->name ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Type</dt>
                <dd style="padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;"><span style="padding:2px 8px;background:{{ $c[0] }};color:{{ $c[1] }};border-radius:12px;font-size:.75rem;font-weight:600;text-transform:capitalize;">{{ $log->type }}</span></dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Old Bandwidth</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $log->old_bandwidth ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">New Bandwidth</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $log->new_bandwidth ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Request Date</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $log->request_date ? \Carbon\Carbon::parse($log->request_date)->format('d M Y') : '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Execute Date</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $log->execute_date ? \Carbon\Carbon::parse($log->execute_date)->format('d M Y') : '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;">Notes</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;margin:0;">{{ $log->notes ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
