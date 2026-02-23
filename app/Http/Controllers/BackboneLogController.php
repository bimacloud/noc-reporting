<?php

namespace App\Http\Controllers;

use App\Models\BackboneLog;
use App\Http\Requests\StoreBackboneLogRequest;
use App\Http\Requests\UpdateBackboneLogRequest;
use App\Models\BackboneLink;

class BackboneLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $logs = BackboneLog::with('backboneLink')->orderBy('request_date', 'desc')->paginate(10);
        return view('backbone_logs.index', compact('logs'));
    }

    public function create()
    {
        $links = BackboneLink::all();
        return view('backbone_logs.create', compact('links'));
    }

    public function store(StoreBackboneLogRequest $request)
    {
        $log = BackboneLog::create($request->validated());
        
        if ($log->new_capacity) {
            $log->backboneLink->update(['capacity' => $log->new_capacity]);
        }
        
        return redirect()->route('backbone_logs.index')->with('success', 'Backbone log entry created successfully.');
    }

    public function show(BackboneLog $backboneLog)
    {
        return view('backbone_logs.show', compact('backboneLog'));
    }

    public function edit(BackboneLog $backboneLog)
    {
        $links = BackboneLink::all();
        return view('backbone_logs.edit', compact('backboneLog', 'links'));
    }

    public function update(UpdateBackboneLogRequest $request, BackboneLog $backboneLog)
    {
        $backboneLog->update($request->validated());
        
        if ($backboneLog->new_capacity) {
            $backboneLog->backboneLink->update(['capacity' => $backboneLog->new_capacity]);
        }
        
        return redirect()->route('backbone_logs.index')->with('success', 'Backbone log entry updated successfully.');
    }

    public function destroy(BackboneLog $backboneLog)
    {
        $backboneLog->delete();
        return redirect()->route('backbone_logs.index')->with('success', 'Backbone log entry deleted successfully.');
    }
}
