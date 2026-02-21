<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('customers.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Edit Customer: {{ $customer->name }}</h1>
        </div>
    </x-slot>
    <div style="max-width:540px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('customers.update', $customer) }}">
                @csrf @method('PUT')
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Customer Name *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required style="{{ $inp }}">
                    @error('name') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Registration Date</label>
                    <input type="date" name="registration_date" value="{{ old('registration_date', $customer->registration_date) }}" style="{{ $inp }}">
                    @error('registration_date') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Address</label>
                    <textarea name="address" style="{{ $inp }}" rows="3">{{ old('address', $customer->address) }}</textarea>
                    @error('address') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Service Type *</label>
                        <select name="service_type_id" required style="{{ $inp }}">
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" {{ old('service_type_id', $customer->service_type_id)==$type->id?'selected':'' }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                        @error('service_type_id') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Bandwidth</label>
                        <input type="text" name="bandwidth" value="{{ old('bandwidth', $customer->bandwidth) }}" style="{{ $inp }}">
                    </div>
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Status</label>
                    <select name="status" style="{{ $inp }}">
                        @foreach(['active','inactive','suspended'] as $s)
                            <option value="{{ $s }}" {{ old('status', $customer->status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Update</button>
                    <a href="{{ route('customers.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
