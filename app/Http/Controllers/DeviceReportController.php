<?php

namespace App\Http\Controllers;

use App\Models\DeviceReport;
use App\Models\Device;
use App\Http\Requests\StoreDeviceReportRequest;
use App\Http\Requests\UpdateDeviceReportRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DeviceReportController extends Controller
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
        $query = DeviceReport::with('device');

        if ($request->filled('month')) {
            $query->whereDate('month', $request->month . '-01');
        }

        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        $reports = $query->latest('month')->paginate(20)->withQueryString();
        $devices = Device::all();

        return view('device_reports.index', compact('reports', 'devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devices = Device::all();
        return view('device_reports.create', compact('devices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceReportRequest $request)
    {
        DeviceReport::create($request->validated());

        return redirect()->route('device_reports.index')
            ->with('success', 'Device report created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DeviceReport $deviceReport)
    {
        return view('device_reports.show', compact('deviceReport'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeviceReport $deviceReport)
    {
        $devices = Device::all();
        return view('device_reports.edit', compact('deviceReport', 'devices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceReportRequest $request, DeviceReport $deviceReport)
    {
        $deviceReport->update($request->validated());

        return redirect()->route('device_reports.index')
            ->with('success', 'Device report updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeviceReport $deviceReport)
    {
        $deviceReport->delete();

        return redirect()->route('device_reports.index')
            ->with('success', 'Device report deleted successfully.');
    }
}
