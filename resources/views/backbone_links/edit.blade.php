<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('backbone_links.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Edit Backbone Link</h1>
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('backbone_links.update', $backboneLink) }}">
                @csrf @method('PUT')
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Node A *</label>
                        <input type="text" name="node_a" value="{{ old('node_a', $backboneLink->node_a) }}" required style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Node B *</label>
                        <input type="text" name="node_b" value="{{ old('node_b', $backboneLink->node_b) }}" required style="{{ $inp }}">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:24px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Provider</label>
                        <input type="text" name="provider" value="{{ old('provider', $backboneLink->provider) }}" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Media</label>
                        <select name="media" style="{{ $inp }}">
                            <option value="">— Select —</option>
                            @foreach(['Fiber Optic','Metro Ethernet','Microwave','Leased Line','Other'] as $m)
                                <option value="{{ $m }}" {{ old('media',$backboneLink->media)===$m?'selected':'' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Capacity</label>
                        <input type="text" name="capacity" value="{{ old('capacity', $backboneLink->capacity) }}" style="{{ $inp }}">
                    </div>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Update</button>
                    <a href="{{ route('backbone_links.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
