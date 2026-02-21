<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <a href="{{ route('upstream_reports.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Upstream Report — {{ $report->upstream->peer_name ?? '—' }} / {{ $report->month ? \Carbon\Carbon::parse($report->month)->format('M Y') : '' }}</h1>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('upstream_reports.edit', $report) }}" style="padding:7px 14px;background:#dbeafe;color:#1d4ed8;border-radius:6px;font-size:.875rem;text-decoration:none;">Edit</a>
            @endif
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            @php
                $sb = fn($v) => '<span style="padding:2px 8px;background:'.match($v){\'up\',\'good\'=> \'#d1fae5\', \'down\' => \'#fee2e2\', default => \'#fef3c7\'}.';color:'.match($v){\'up\',\'good\'=> \'#065f46\', \'down\' => \'#991b1b\', default => \'#92400e\'}.';border-radius:12px;font-size:.75rem;font-weight:600;">'.ucfirst($v?? \'—\').'</span>';
            @endphp
            <dl style="display:grid;grid-template-columns:160px 1fr;gap:0;">
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Upstream</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $report->upstream->peer_name ?? '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Month</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $report->month ? \Carbon\Carbon::parse($report->month)->format('F Y') : '—' }}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">L1 Status</dt>
                <dd style="padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{!! $sb($report->status_l1) !!}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">L2 Status</dt>
                <dd style="padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{!! $sb($report->status_l2) !!}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">L3 Status</dt>
                <dd style="padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{!! $sb($report->status_l3) !!}</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">Duration</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $report->duration ?? 0 }} minutes</dd>
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;">Notes</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;margin:0;">{{ $report->notes ?? '—' }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
