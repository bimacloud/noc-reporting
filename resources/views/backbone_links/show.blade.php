<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <a href="{{ route('backbone_links.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">{{ $backboneLink->node_a }} → {{ $backboneLink->node_b }}</h1>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('backbone_links.edit', $backboneLink) }}" style="padding:7px 14px;background:#dbeafe;color:#1d4ed8;border-radius:6px;font-size:.875rem;text-decoration:none;">Edit</a>
            @endif
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <dl style="display:grid;grid-template-columns:160px 1fr;gap:0;">
                @foreach([['Node A',$backboneLink->node_a],['Node B',$backboneLink->node_b],['Provider',$backboneLink->provider??'—'],['Media',$backboneLink->media??'—'],['Capacity',$backboneLink->capacity??'—'],['Created',$backboneLink->created_at->format('d M Y')]] as [$l,$v])
                <dt style="font-size:.8125rem;font-weight:500;color:#6b7280;padding:10px 0;border-bottom:1px solid #f3f4f6;">{{ $l }}</dt>
                <dd style="font-size:.875rem;color:#111827;padding:10px 0;border-bottom:1px solid #f3f4f6;margin:0;">{{ $v }}</dd>
                @endforeach
            </dl>
        </div>
    </div>
</x-app-layout>
