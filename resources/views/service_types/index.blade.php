<x-app-layout>
    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h1 style="font-size:1.125rem;font-weight:700;color:#111827;margin:0;">Service Types</h1>
                <p style="font-size:.875rem;color:#6b7280;margin:4px 0 0;">Manage service categories for customers.</p>
            </div>
            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
            <a href="{{ route('service_types.create') }}" style="padding:9px 16px;background:#2563eb;color:#fff;border-radius:6px;font-size:.875rem;font-weight:500;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Add Type
            </a>
            @endif
        </div>
    </x-slot>

    <div style="background:#fff;border-radius:10px;border:1px solid #e5e7eb;box-shadow:0 1px 3px rgba(0,0,0,.06);overflow:hidden;max-width:800px;">
        <table style="width:100%;border-collapse:collapse;text-align:left;">
            <thead style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                <tr>
                    <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Name</th>
                    <th style="padding:12px 16px;font-size:.75rem;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;">Updated</th>
                    <th style="padding:12px 16px;width:100px;"></th>
                </tr>
            </thead>
            <tbody style="divide-y:1px solid #e5e7eb;">
                @forelse($types as $type)
                <tr style="hover:bg-slate-50">
                    <td style="padding:10px 16px;font-size:.875rem;color:#111827;font-weight:500;">
                        {{ $type->name }}
                    </td>
                    <td style="padding:10px 16px;font-size:.75rem;color:#6b7280;">{{ $type->updated_at->diffForHumans() }}</td>
                    <td style="padding:10px 16px;text-align:right;">
                        <div style="display:flex;justify-content:flex-end;gap:8px;">
                            @if(Auth::user()->isAdmin() || Auth::user()->isNoc())
                            <a href="{{ route('service_types.edit', $type) }}" style="color:#2563eb;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                            <form action="{{ route('service_types.destroy', $type) }}" method="POST" onsubmit="return confirm('Are you sure? This might affect existing customers.');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;padding:0;color:#ef4444;cursor:pointer;"><svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding:24px;text-align:center;color:#6b7280;font-size:.875rem;">No service types found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($types->hasPages())
        <div style="padding:12px 16px;border-top:1px solid #e5e7eb;">
            {{ $types->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
