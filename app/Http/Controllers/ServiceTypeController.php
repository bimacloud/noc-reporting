<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $types = ServiceType::paginate(10);
        return view('service_types.index', compact('types'));
    }

    public function create()
    {
        return view('service_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name',
        ]);

        ServiceType::create($request->all());

        return redirect()->route('service_types.index')
            ->with('success', 'Service Type created successfully.');
    }

    public function show(ServiceType $serviceType)
    {
        return view('service_types.edit', ['serviceType' => $serviceType]);
    }

    public function edit(ServiceType $serviceType)
    {
        return view('service_types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name,' . $serviceType->id,
        ]);

        $serviceType->update($request->all());

        return redirect()->route('service_types.index')
            ->with('success', 'Service Type updated successfully.');
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();

        return redirect()->route('service_types.index')
            ->with('success', 'Service Type deleted successfully.');
    }
}
