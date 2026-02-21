<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpstreamRequest;
use App\Http\Requests\UpdateUpstreamRequest;
use App\Models\Upstream;
use App\Models\Location;

class UpstreamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,noc')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $upstreams = Upstream::with('location')->paginate(10);
        return view('upstreams.index', compact('upstreams'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('upstreams.create', compact('locations'));
    }

    public function store(StoreUpstreamRequest $request)
    {
        Upstream::create($request->validated());
        return redirect()->route('upstreams.index')->with('success', 'Upstream created successfully.');
    }

    public function show(Upstream $upstream)
    {
        return view('upstreams.show', compact('upstream'));
    }

    public function edit(Upstream $upstream)
    {
        $locations = Location::all();
        return view('upstreams.edit', compact('upstream', 'locations'));
    }

    public function update(UpdateUpstreamRequest $request, Upstream $upstream)
    {
        $upstream->update($request->validated());
        return redirect()->route('upstreams.index')->with('success', 'Upstream updated successfully.');
    }

    public function destroy(Upstream $upstream)
    {
        $upstream->delete();
        return redirect()->route('upstreams.index')->with('success', 'Upstream deleted successfully.');
    }
}
