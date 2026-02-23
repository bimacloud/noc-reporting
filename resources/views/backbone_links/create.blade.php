<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('backbone_links.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Add Backbone Link</h1>
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div x-data="{ 
            showC: {{ old('node_c') ? 'true' : 'false' }},
            showD: {{ old('node_d') ? 'true' : 'false' }},
            showE: {{ old('node_e') ? 'true' : 'false' }}
        }" style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('backbone_links.store') }}">
                @csrf
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Node A <span style="color:#dc2626;">*</span></label>
                        <select name="node_a" required style="{{ $inp }}">
                            <option value="">— Select Node A —</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->name }}" {{ old('node_a') === $site->name ? 'selected' : '' }}>
                                    {{ $site->name }} ({{ $site->siteGroup->name ?? 'No Group' }})
                                </option>
                            @endforeach
                        </select>
                        @error('node_a') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Node B <span style="color:#dc2626;">*</span></label>
                        <select name="node_b" required style="{{ $inp }}">
                            <option value="">— Select Node B —</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->name }}" {{ old('node_b') === $site->name ? 'selected' : '' }}>
                                    {{ $site->name }} ({{ $site->siteGroup->name ?? 'No Group' }})
                                </option>
                            @endforeach
                        </select>
                        @error('node_b') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:16px;">
                    <div x-show="showC" x-transition>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Node C</label>
                        <div style="display:flex;gap:8px;">
                            <select name="node_c" style="{{ $inp }}">
                                <option value="">— Optional Node C —</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->name }}" {{ old('node_c') === $site->name ? 'selected' : '' }}>
                                        {{ $site->name }} ({{ $site->siteGroup->name ?? 'No Group' }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" @click="showC = false; document.querySelector('select[name=\'node_c\']').value = '';" style="padding:0 12px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;border-radius:6px;cursor:pointer;">✕</button>
                        </div>
                        @error('node_c') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="showD" x-transition>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Node D</label>
                        <div style="display:flex;gap:8px;">
                            <select name="node_d" style="{{ $inp }}">
                                <option value="">— Optional Node D —</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->name }}" {{ old('node_d') === $site->name ? 'selected' : '' }}>
                                        {{ $site->name }} ({{ $site->siteGroup->name ?? 'No Group' }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" @click="showD = false; document.querySelector('select[name=\'node_d\']').value = '';" style="padding:0 12px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;border-radius:6px;cursor:pointer;">✕</button>
                        </div>
                        @error('node_d') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="showE" x-transition>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Node E</label>
                        <div style="display:flex;gap:8px;">
                            <select name="node_e" style="{{ $inp }}">
                                <option value="">— Optional Node E —</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->name }}" {{ old('node_e') === $site->name ? 'selected' : '' }}>
                                        {{ $site->name }} ({{ $site->siteGroup->name ?? 'No Group' }})
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" @click="showE = false; document.querySelector('select[name=\'node_e\']').value = '';" style="padding:0 12px;background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;border-radius:6px;cursor:pointer;">✕</button>
                        </div>
                        @error('node_e') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div x-show="!showC || !showD || !showE" style="margin-bottom:24px;">
                    <button type="button" @click="if(!showC){ showC=true; } else if(!showD){ showD=true; } else if(!showE){ showE=true; }" style="padding:6px 14px;background:#f3f4f6;color:#374151;border:1px dashed #d1d5db;border-radius:6px;font-size:.8125rem;font-weight:500;cursor:pointer;width:100%;text-align:center;">
                        + Add Additional Node
                    </button>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:24px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Provider / NAP</label>
                        <select name="provider" style="{{ $inp }}">
                            <option value="">— Select Provider —</option>
                            @foreach($providers as $prov)
                                <option value="{{ $prov->name }}" {{ old('provider') == $prov->name ? 'selected' : '' }}>{{ $prov->name }} ({{ $prov->type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Media</label>
                        <select name="media" style="{{ $inp }}">
                            <option value="">— Select —</option>
                            @foreach(['Fiber Optic','Metro Ethernet','Microwave','Leased Line','Other'] as $m)
                                <option value="{{ $m }}" {{ old('media')===$m?'selected':'' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Capacity</label>
                        <input type="text" name="capacity" value="{{ old('capacity') }}" style="{{ $inp }}" placeholder="e.g. 10 Gbps">
                    </div>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Save Link</button>
                    <a href="{{ route('backbone_links.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
