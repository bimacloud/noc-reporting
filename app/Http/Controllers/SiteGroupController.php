<?php

namespace App\Http\Controllers;

use App\Models\SiteGroup;

class SiteGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $siteGroups = SiteGroup::withCount('sites')
                               ->orderBy('name')
                               ->get();

        return view('site-groups.index', compact('siteGroups'));
    }
}
