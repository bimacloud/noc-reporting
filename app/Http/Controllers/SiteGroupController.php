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
                               ->paginate(50);

        return view('site-groups.index', compact('siteGroups'));
    }
}
