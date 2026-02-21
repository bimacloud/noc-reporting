<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('upstreams.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Add Upstream</h1>
        </div>
    </x-slot>
    <div style="max-width:540px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('upstreams.store') }}">
                @csrf
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Peer Name *</label>
                    <input type="text" name="peer_name" value="{{ old('peer_name') }}" required style="{{ $inp }}">
                    @error('peer_name') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Capacity</label>
                        <input type="text" name="capacity" value="{{ old('capacity') }}" style="{{ $inp }}" placeholder="e.g. 10 Gbps">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Location</label>
                        <select name="location_id" style="{{ $inp }}">
                            <option value="">— None —</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id')==$loc->id?'selected':'' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Save</button>
                    <a href="{{ route('upstreams.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
