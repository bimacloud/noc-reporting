<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('backbone_incidents.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Edit Backbone Incident</h1>
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('backbone_incidents.update', $incident) }}">
                @csrf @method('PUT')
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Backbone Link</label>
                        <select name="backbone_link_id" style="{{ $inp }}">
                            @foreach($backboneLinks as $link)
                                <option value="{{ $link->id }}" {{ old('backbone_link_id', $incident->backbone_link_id)==$link->id?'selected':'' }}>{{ $link->node_a }} → {{ $link->node_b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Incident Date</label>
                        <input type="datetime-local" name="incident_date" value="{{ old('incident_date', $incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('Y-m-d\TH:i') : '') }}" style="{{ $inp }}">
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Latency (ms)</label>
                        <input type="number" name="latency" value="{{ old('latency', $incident->latency) }}" min="0" step="0.01" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Duration (min)</label>
                        <input type="number" name="duration" value="{{ old('duration', $incident->duration) }}" min="0" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Down?</label>
                        <select name="down_status" style="{{ $inp }}">
                            <option value="0" {{ !old('down_status', $incident->down_status)?'selected':'' }}>Up</option>
                            <option value="1" {{ old('down_status', $incident->down_status)?'selected':'' }}>Down</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Description</label>
                    <textarea name="description" rows="3" style="{{ $inp }}">{{ old('description', $incident->description) }}</textarea>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Update</button>
                    <a href="{{ route('backbone_incidents.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
