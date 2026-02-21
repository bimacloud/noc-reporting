<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $types = DeviceType::paginate(10);
        return view('device_types.index', compact('types'));
    }

    public function create()
    {
        return view('device_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:device_types,name',
        ]);

        DeviceType::create($request->all());

        return redirect()->route('device_types.index')
            ->with('success', 'Device Type created successfully.');
    }

    public function show(DeviceType $deviceType)
    {
        // Not implemented (no detailed view needed typically for simple types)
        // But we can redirect to Edit or just show nothing.
        // For consistency, let's skip or show simple view.
        return view('device_types.edit', ['deviceType' => $deviceType]); 
    }

    public function edit(DeviceType $deviceType)
    {
        return view('device_types.edit', compact('deviceType'));
    }

    public function update(Request $request, DeviceType $deviceType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:device_types,name,' . $deviceType->id,
        ]);

        $deviceType->update($request->all());

        return redirect()->route('device_types.index')
            ->with('success', 'Device Type updated successfully.');
    }

    public function destroy(DeviceType $deviceType)
    {
        $deviceType->delete();

        return redirect()->route('device_types.index')
            ->with('success', 'Device Type deleted successfully.');
    }
}
