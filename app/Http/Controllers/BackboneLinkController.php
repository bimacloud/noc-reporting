<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBackboneLinkRequest;
use App\Http\Requests\UpdateBackboneLinkRequest;
use App\Models\BackboneLink;

class BackboneLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $backboneLinks = BackboneLink::all();
        // Enrich the link data with Site Group information based on the saved Site name
        foreach ($backboneLinks as $link) {
            $siteA = \App\Models\Site::where('name', $link->node_a)->with('siteGroup')->first();
            $siteB = \App\Models\Site::where('name', $link->node_b)->with('siteGroup')->first();
            $siteC = $link->node_c ? \App\Models\Site::where('name', $link->node_c)->with('siteGroup')->first() : null;
            $siteD = $link->node_d ? \App\Models\Site::where('name', $link->node_d)->with('siteGroup')->first() : null;
            $siteE = $link->node_e ? \App\Models\Site::where('name', $link->node_e)->with('siteGroup')->first() : null;

            $link->site_group_a = $siteA?->siteGroup?->name;
            $link->site_group_b = $siteB?->siteGroup?->name;
            $link->site_group_c = $siteC?->siteGroup?->name;
            $link->site_group_d = $siteD?->siteGroup?->name;
            $link->site_group_e = $siteE?->siteGroup?->name;
        }

        return view('backbone_links.index', compact('backboneLinks'));
    }

    public function create()
    {
        $sites = \App\Models\Site::with('siteGroup')->orderBy('name')->get();
        $providers = \App\Models\Provider::orderBy('name')->get();
        return view('backbone_links.create', compact('sites', 'providers'));
    }

    public function store(StoreBackboneLinkRequest $request)
    {
        BackboneLink::create($request->validated());
        return redirect()->route('backbone_links.index')->with('success', 'Backbone Link created successfully.');
    }

    public function show(BackboneLink $backboneLink)
    {
        return view('backbone_links.show', compact('backboneLink'));
    }

    public function edit(BackboneLink $backboneLink)
    {
        $sites = \App\Models\Site::with('siteGroup')->orderBy('name')->get();
        $providers = \App\Models\Provider::orderBy('name')->get();
        return view('backbone_links.edit', compact('backboneLink', 'sites', 'providers'));
    }

    public function update(UpdateBackboneLinkRequest $request, BackboneLink $backboneLink)
    {
        $backboneLink->update($request->validated());
        return redirect()->route('backbone_links.index')->with('success', 'Backbone Link updated successfully.');
    }

    public function destroy(BackboneLink $backboneLink)
    {
        $backboneLink->delete();
        return redirect()->route('backbone_links.index')->with('success', 'Backbone Link deleted successfully.');
    }
}
