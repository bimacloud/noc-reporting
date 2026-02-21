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
        $backboneLinks = BackboneLink::paginate(10);
        return view('backbone_links.index', compact('backboneLinks'));
    }

    public function create()
    {
        return view('backbone_links.create');
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
        return view('backbone_links.edit', compact('backboneLink'));
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
