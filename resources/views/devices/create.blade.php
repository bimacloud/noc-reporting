<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('devices.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Add Device</h1>
        </div>
    </x-slot>
    <div style="max-width:540px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('devices.store') }}">
                @csrf
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Device Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required style="{{ $inp }}">
                    @error('name') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Type *</label>
                        <select name="device_type_id" required style="{{ $inp }}">
                            <option value="">— Select Type —</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('device_type_id')==$type->id?'selected':'' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('device_type_id') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Location *</label>
                        <select name="location_id" required style="{{ $inp }}">
                            <option value="">— Select Location —</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('location_id')==$loc->id?'selected':'' }}>{{ $loc->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:24px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Model</label>
                        <input type="text" name="model" value="{{ old('model') }}" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Status</label>
                        <select name="status" style="{{ $inp }}">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Serial Number</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number') }}" style="{{ $inp }}">
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Save</button>
                    <a href="{{ route('devices.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
