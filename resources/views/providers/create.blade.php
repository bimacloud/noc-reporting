<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('providers.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Add Provider / NAP</h1>
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('providers.store') }}">
                @csrf
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;margin-bottom:16px;'; @endphp
                
                <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Type <span style="color:#dc2626;">*</span></label>
                <select name="type" required style="{{ $inp }}">
                    <option value="Provider" {{ old('type') == 'Provider' ? 'selected' : '' }}>Provider</option>
                    <option value="NAP" {{ old('type') == 'NAP' ? 'selected' : '' }}>NAP</option>
                </select>
                @error('type') <p style="color:#dc2626;font-size:.75rem;margin-top:-10px;margin-bottom:16px;">{{ $message }}</p> @enderror

                <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Name <span style="color:#dc2626;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required style="{{ $inp }}">
                @error('name') <p style="color:#dc2626;font-size:.75rem;margin-top:-10px;margin-bottom:16px;">{{ $message }}</p> @enderror

                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Save</button>
                    <a href="{{ route('providers.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
