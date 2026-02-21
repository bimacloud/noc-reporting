<?php

namespace App\Http\Controllers;

use App\Models\UpstreamReport;
use App\Models\Upstream;
use App\Http\Requests\StoreUpstreamReportRequest;
use App\Http\Requests\UpdateUpstreamReportRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UpstreamReportController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = UpstreamReport::with('upstream');

        if ($request->filled('month')) {
            $query->whereDate('month', $request->month . '-01');
        }

        if ($request->filled('upstream_id')) {
            $query->where('upstream_id', $request->upstream_id);
        }

        $reports = $query->latest('month')->paginate(20)->withQueryString();
        $upstreams = Upstream::all();

        return view('upstream_reports.index', compact('reports', 'upstreams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $upstreams = Upstream::all();
        return view('upstream_reports.create', compact('upstreams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUpstreamReportRequest $request)
    {
        UpstreamReport::create($request->validated());

        return redirect()->route('upstream_reports.index')
            ->with('success', 'Upstream report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UpstreamReport $upstreamReport)
    {
        return view('upstream_reports.show', compact('upstreamReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UpstreamReport $upstreamReport)
    {
        $upstreams = Upstream::all();
        return view('upstream_reports.edit', compact('upstreamReport', 'upstreams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUpstreamReportRequest $request, UpstreamReport $upstreamReport)
    {
        $upstreamReport->update($request->validated());

        return redirect()->route('upstream_reports.index')
            ->with('success', 'Upstream report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UpstreamReport $upstreamReport)
    {
        $upstreamReport->delete();

        return redirect()->route('upstream_reports.index')
            ->with('success', 'Upstream report deleted successfully.');
    }
}
