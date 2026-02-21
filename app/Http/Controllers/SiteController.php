<?php

namespace App\Http\Controllers;

use App\Models\Site;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sites = Site::with('siteGroup')
                     ->withCount('locations', 'devices')
                     ->orderBy('name')
                     ->paginate(20);

        return view('sites.index', compact('sites'));
    }
}
