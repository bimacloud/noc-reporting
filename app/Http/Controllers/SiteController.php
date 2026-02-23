<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $query = Site::with('siteGroup')
                     ->withCount('locations', 'devices')
                     ->orderBy('name');

        if ($request->has('site_group_id')) {
            $query->where('site_group_id', $request->input('site_group_id'));
        }

        $sites = $query->get();

        return view('sites.index', compact('sites'));
    }
}
