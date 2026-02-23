<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Models\Device;
use App\Models\Location;
use App\Models\DeviceType;

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $query = Device::with(['location', 'deviceType']);

        if ($request->has('site_id')) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('site_id', $request->input('site_id'));
            });
        }

        $devices = $query->get();
        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        $locations = Location::all();
        $types = DeviceType::all();
        return view('devices.create', compact('locations', 'types'));
    }

    public function store(StoreDeviceRequest $request)
    {
        Device::create($request->validated());
        return redirect()->route('devices.index')->with('success', 'Device created successfully.');
    }

    public function show(Device $device)
    {
        return view('devices.show', compact('device'));
    }

    public function edit(Device $device)
    {
        $locations = Location::all();
        $types = DeviceType::all();
        return view('devices.edit', compact('device', 'locations', 'types'));
    }

    public function update(UpdateDeviceRequest $request, Device $device)
    {
        $device->update($request->validated());
        return redirect()->route('devices.index')->with('success', 'Device updated successfully.');
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted successfully.');
    }
}
