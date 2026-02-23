<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('backbone_logs.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Edit Backbone Change Log</h1>
        </div>
    </x-slot>
    <div style="max-width:600px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('backbone_logs.update', $backboneLog) }}">
                @csrf @method('PUT')
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Backbone Link *</label>
                    <select name="backbone_link_id" required style="{{ $inp }}">
                        @foreach($links as $link)
                            <option value="{{ $link->id }}" {{ old('backbone_link_id', $backboneLog->backbone_link_id)==$link->id?'selected':'' }}>
                                {{ $link->node_a }} - {{ $link->node_b }} ({{ $link->provider }})
                            </option>
                        @endforeach
                    </select>
                    @error('backbone_link_id') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                </div>
                
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Type of Change *</label>
                    <select name="type" required style="{{ $inp }}">
                        <option value="activation" {{ old('type', $backboneLog->type)=='activation'?'selected':'' }}>Activation</option>
                        <option value="upgrade" {{ old('type', $backboneLog->type)=='upgrade'?'selected':'' }}>Upgrade</option>
                        <option value="downgrade" {{ old('type', $backboneLog->type)=='downgrade'?'selected':'' }}>Downgrade</option>
                        <option value="deactivation" {{ old('type', $backboneLog->type)=='deactivation'?'selected':'' }}>Deactivation</option>
                    </select>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Old Capacity</label>
                        <input type="text" name="old_capacity" value="{{ old('old_capacity', $backboneLog->old_capacity) }}" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">New Capacity</label>
                        <input type="text" name="new_capacity" value="{{ old('new_capacity', $backboneLog->new_capacity) }}" style="{{ $inp }}">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Request Date *</label>
                        <input type="date" name="request_date" required value="{{ old('request_date', $backboneLog->request_date) }}" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Execute Date</label>
                        <input type="date" name="execute_date" value="{{ old('execute_date', $backboneLog->execute_date) }}" style="{{ $inp }}">
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Notes</label>
                    <textarea name="notes" rows="3" style="{{ $inp }}">{{ old('notes', $backboneLog->notes) }}</textarea>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Update Record</button>
                    <a href="{{ route('backbone_logs.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
