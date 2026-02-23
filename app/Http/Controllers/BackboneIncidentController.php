<?php

namespace App\Http\Controllers;

use App\Models\BackboneIncident;
use App\Models\BackboneLink;
use App\Http\Requests\StoreBackboneIncidentRequest;
use App\Http\Requests\UpdateBackboneIncidentRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BackboneIncidentController extends Controller
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
        $query = BackboneIncident::with('backboneLink');

        if ($request->filled('month')) {
            $query->whereMonth('incident_date', date('m', strtotime($request->month)))
                  ->whereYear('incident_date', date('Y', strtotime($request->month)));
        }

        if ($request->filled('backbone_link_id')) {
            $query->where('backbone_link_id', $request->backbone_link_id);
        }

        $incidents = $query->latest('incident_date')->paginate(20)->withQueryString();
        $backboneLinks = BackboneLink::all();

        return view('backbone_incidents.index', compact('incidents', 'backboneLinks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $backboneLinks = BackboneLink::all();
        return view('backbone_incidents.create', compact('backboneLinks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBackboneIncidentRequest $request)
    {
        $data = $request->validated();
        
        if (empty($data['duration']) && !empty($data['resolve_date'])) {
            $incidentDate = \Carbon\Carbon::parse($data['incident_date']);
            $resolveDate = \Carbon\Carbon::parse($data['resolve_date']);
            $data['duration'] = $incidentDate->diffInMinutes($resolveDate);
        } elseif (empty($data['duration'])) {
            $data['duration'] = 0;
        }

        BackboneIncident::create($data);

        return redirect()->route('backbone_incidents.index')
            ->with('success', 'Backbone incident created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BackboneIncident $backboneIncident)
    {
        return view('backbone_incidents.show', ['incident' => $backboneIncident]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BackboneIncident $backboneIncident)
    {
        $backboneLinks = BackboneLink::all();
        return view('backbone_incidents.edit', compact('backboneIncident', 'backboneLinks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBackboneIncidentRequest $request, BackboneIncident $backboneIncident)
    {
        $data = $request->validated();
        
        if (empty($data['duration']) && !empty($data['resolve_date'])) {
            $incidentDate = \Carbon\Carbon::parse($data['incident_date']);
            $resolveDate = \Carbon\Carbon::parse($data['resolve_date']);
            $data['duration'] = $incidentDate->diffInMinutes($resolveDate);
        } elseif (empty($data['duration'])) {
            $data['duration'] = 0;
        }

        $backboneIncident->update($data);

        return redirect()->route('backbone_incidents.index')
            ->with('success', 'Backbone incident updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BackboneIncident $backboneIncident)
    {
        $backboneIncident->delete();

        return redirect()->route('backbone_incidents.index')
            ->with('success', 'Backbone incident deleted successfully.');
    }

    public function resolveForm(BackboneIncident $incident)
    {
        return view('backbone_incidents.resolve', compact('incident'));
    }

    public function resolve(Request $request, BackboneIncident $incident)
    {
        $data = $request->validate([
            'resolve_date' => 'required|date|after_or_equal:incident_date',
            'notes' => 'required|string',
        ]);

        $incidentDate = \Carbon\Carbon::parse($incident->incident_date);
        $resolveDate = \Carbon\Carbon::parse($data['resolve_date']);
        $duration = $incidentDate->diffInMinutes($resolveDate);

        $incident->update([
            'resolve_date' => $data['resolve_date'],
            'notes' => $data['notes'],
            'duration' => $duration,
        ]);

        return redirect()->route('backbone_incidents.index')
            ->with('success', 'Backbone incident resolved successfully.');
    }
}
