<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container .select2-selection--single {
                height: 38px !important;
                border: 1px solid #d1d5db !important;
                border-radius: 6px !important;
                display: flex;
                align-items: center;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px !important;
                right: 8px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #111827 !important;
                font-size: .875rem !important;
                padding-left: 0 !important;
            }
        </style>
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="{{ route('service_logs.index') }}" style="color:#6b7280;text-decoration:none;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg></a>
            <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Add Service Log</h1>
        </div>
    </x-slot>
    <div style="max-width:640px;">
        <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.06);">
            <form method="POST" action="{{ route('service_logs.store') }}">
                @csrf
                @php $inp = 'width:100%;border:1px solid #d1d5db;border-radius:6px;padding:8px 12px;font-size:.875rem;color:#111827;box-sizing:border-box;'; @endphp

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Customer *</label>
                        <select name="customer_id" id="customer_id" required style="{{ $inp }}">
                            <option value="" data-bandwidth="">— Select Customer —</option>
                            @foreach($customers as $cust)
                                <option value="{{ $cust->id }}" data-bandwidth="{{ $cust->bandwidth }}" {{ old('customer_id')==$cust->id?'selected':'' }}>{{ $cust->name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Service Type *</label>
                        <select name="type" required style="{{ $inp }}">
                            <option value="">— Select —</option>
                            @foreach(['activation','upgrade','downgrade','suspension','deactivation','termination'] as $t)
                                <option value="{{ $t }}" {{ old('type')===$t?'selected':'' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                        @error('type') <p style="color:#dc2626;font-size:.75rem;margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Old Bandwidth</label>
                        <input type="text" name="old_bandwidth" id="old_bandwidth" value="{{ old('old_bandwidth') }}" style="{{ $inp }}" placeholder="e.g. 50 Mbps">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">New Bandwidth</label>
                        <input type="text" name="new_bandwidth" value="{{ old('new_bandwidth') }}" style="{{ $inp }}" placeholder="e.g. 100 Mbps">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Request Date</label>
                        <input type="date" name="request_date" value="{{ old('request_date', date('Y-m-d')) }}" style="{{ $inp }}">
                    </div>
                    <div>
                        <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Execute Date</label>
                        <input type="date" name="execute_date" value="{{ old('execute_date') }}" style="{{ $inp }}">
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:.875rem;font-weight:500;color:#374151;margin-bottom:6px;">Notes</label>
                    <textarea name="notes" rows="3" style="{{ $inp }}">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;gap:10px;">
                    <button type="submit" style="padding:9px 20px;background:#2563eb;color:#fff;border:none;border-radius:6px;font-size:.875rem;font-weight:500;cursor:pointer;">Save Log</button>
                    <a href="{{ route('service_logs.index') }}" style="padding:9px 20px;background:#f3f4f6;color:#374151;border-radius:6px;font-size:.875rem;text-decoration:none;">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#customer_id').select2({
                placeholder: '— Select Customer —',
                width: '100%'
            }).on('select2:select', function (e) {
                var bandwidth = $(e.params.data.element).data('bandwidth') || '';
                document.getElementById('old_bandwidth').value = bandwidth;
            });
        });
    </script>
    @endpush
</x-app-layout>
