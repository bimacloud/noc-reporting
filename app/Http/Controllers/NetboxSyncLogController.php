<?php

namespace App\Http\Controllers;

use App\Models\NetboxSyncLog;
use Illuminate\Http\Request;

class NetboxSyncLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $type   = $request->input('type');
        $status = $request->input('status');

        $logs = NetboxSyncLog::query()
            ->when($type,   fn($q) => $q->where('entity_type', $type))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('synced_at')
            ->paginate(50)
            ->withQueryString();

        // Summary stats
        $stats = NetboxSyncLog::selectRaw('entity_type, status, count(*) as total')
            ->groupBy('entity_type', 'status')
            ->get()
            ->groupBy('entity_type');

        return view('netbox-sync-logs.index', compact('logs', 'stats', 'type', 'status'));
    }

    public function destroy()
    {
        NetboxSyncLog::truncate();
        return redirect()->route('netbox.sync.logs')->with('success', 'Log sync berhasil dihapus.');
    }
}
