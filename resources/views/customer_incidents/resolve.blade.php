<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('customer_incidents.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Resolve Incident #{{ $incident->id }}</h1>
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <div style="margin-bottom:20px;padding-bottom:15px;border-bottom:1px solid #f3f4f6;">
                <p style="font-size:0.875rem;color:#374151;margin:0 0 5px 0;"><strong>Customer:</strong> {{ $incident->customer->name ?? 'Unknown' }}</p>
                <p style="font-size:0.875rem;color:#374151;margin:0;"><strong>Started:</strong> {{ \Carbon\Carbon::parse($incident->incident_date)->format('d M Y, H:i') }}</p>
            </div>
            
            <form method="POST" action="{{ route('customer_incidents.resolve', $incident) }}">
                @csrf @method('PUT')
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp
                
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Resolve Date *</label>
                    <input type="datetime-local" name="resolve_date" value="{{ old('resolve_date', \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('Y-m-d\TH:i')) }}" required style="{{ $inp }}">
                    @error('resolve_date') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Reason / Resolution Notes *</label>
                    <textarea name="notes" rows="4" required style="{{ $inp }}" placeholder="Describe what caused the incident and how it was resolved...">{{ old('notes', $incident->notes) }}</textarea>
                    @error('notes') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                
                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#10b981;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Mark as Resolved</button>
                    <a href="{{ route('customer_incidents.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
