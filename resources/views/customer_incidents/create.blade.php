<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('customer_incidents.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Log Customer Incident</h1>
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('customer_incidents.store') }}">
                @csrf
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Customer *</label>
                        <select name="customer_id" required style="{{ $inp }}">
                            <option value="">— Select Customer —</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" {{ old('customer_id')==$cust->id?'selected':'' }}>{{ $cust->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Incident Date *</label>
                        <input type="datetime-local" name="incident_date" value="{{ old('incident_date', now()->format('Y-m-d\TH:i')) }}" required style="{{ $inp }}">
                        @error('incident_date') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Duration (minutes)</label>
                        <input type="number" name="duration" value="{{ old('duration', 0) }}" min="0" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Status</label>
                        <select name="status" style="{{ $inp }}">
                            <option value="open" {{ old('status','open')==='open'?'selected':'' }}>Open</option>
                            <option value="closed" {{ old('status')==='closed'?'selected':'' }}>Closed</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Root Cause</label>
                    <input type="text" name="root_cause" value="{{ old('root_cause') }}" style="{{ $inp }}" placeholder="What caused the incident?">
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Description</label>
                    <textarea name="description" rows="3" style="{{ $inp }}" placeholder="Incident details...">{{ old('description') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#dc2626;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Log Incident</button>
                    <a href="{{ route('customer_incidents.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
