{{--
    Sidebar Navigation
    Role access:
    - admin: all menus
    - noc: all except user management
    - manager: Dashboard, Monitoring, Reports only
--}}

@php
    $user = Auth::user();
    $isAdmin    = $user->isAdmin();
    $isNoc      = $user->isNoc();
    $isManager  = $user->isManager();

    $canMaster     = $isAdmin || $isNoc;
    $canIncident   = $isAdmin || $isNoc;
    $canService    = $isAdmin || $isNoc;
    $canMonitor    = true; // all roles
    $canReports    = true; // all roles

    $openIncidents = \App\Models\CustomerIncident::where('status', 'open')->count();
    $activeDevices = \App\Models\Device::where('status', 'active')->count();

    // Check active group for auto-expand on load
    $masterActive   = request()->routeIs('locations.*','devices.*','customers.*','resellers.*','backbone_links.*','upstreams.*','site-groups.*','sites.*','netbox.sync.logs*','providers.*');
    $monitorActive  = request()->routeIs('device_reports.*','backbone_incidents.*','upstream_reports.*');
    $incidentActive = request()->routeIs('customer_incidents.*');
    $serviceActive  = request()->routeIs('service_logs.*', 'backbone_logs.*');
@endphp

{{-- Brand --}}
<div style="display:flex;align-items:center;justify-content:space-between;padding:0 16px;height:56px;background:#030712;border-bottom:1px solid #1f2937;flex-shrink:0;">
    <a href="{{ route('dashboard') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;min-width:0;">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#60a5fa" stroke-width="2" style="flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
        </svg>
        <span style="font-weight:700;font-size:1rem;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">NOC Reporting</span>
    </a>
    {{-- Mobile close button --}}
    <button onclick="closeSidebar()" id="sidebar-close-btn" style="background:none;border:none;cursor:pointer;color:#9ca3af;padding:4px;display:none;">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- User Info --}}
<div style="padding:12px 16px;background:#1f2937;border-bottom:1px solid #374151;flex-shrink:0;">
    <div style="display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:#2563eb;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8125rem;flex-shrink:0;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div style="min-width:0;">
            <p style="font-size:.8125rem;font-weight:600;color:#fff;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $user->name }}</p>
            <p style="font-size:.7rem;color:#9ca3af;margin:0;text-transform:capitalize;">{{ $user->role ?? 'user' }}</p>
        </div>
    </div>
</div>

{{-- Navigation --}}
<nav style="flex:1;overflow-y:auto;padding:8px 0;">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
        class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>

    {{-- Master Data --}}
    @if($canMaster)
    <div x-data="{ open: {{ $masterActive ? 'true' : 'false' }} }">
        <button @click="open = !open" class="nav-group-btn">
            <span style="display:flex;align-items:center;gap:10px;">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                </svg>
                Master Data
            </span>
            <svg :class="open ? 'rotated' : ''" class="chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-collapse>
            {{-- NetBox Hierarchy --}}
            <a href="{{ route('site-groups.index') }}" class="nav-subitem {{ request()->routeIs('site-groups.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Site Groups
            </a>
            <a href="{{ route('sites.index') }}" class="nav-subitem {{ request()->routeIs('sites.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                Sites
            </a>
            @if($isAdmin)
            <a href="{{ route('netbox.sync.logs') }}" class="nav-subitem {{ request()->routeIs('netbox.sync.logs*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Sync Log
                @php $errLog = \App\Models\NetboxSyncLog::where('status','error')->count(); @endphp
                @if($errLog > 0)
                    <span class="badge badge-red" style="margin-left:auto;">{{ $errLog > 99 ? '99+' : $errLog }}</span>
                @endif
            </a>
            @endif
            <a href="{{ route('locations.index') }}" class="nav-subitem {{ request()->routeIs('locations.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Locations
            </a>
            <a href="{{ route('devices.index') }}" class="nav-subitem {{ request()->routeIs('devices.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                Devices
                @if($activeDevices > 0)
                    <span class="badge badge-blue" style="margin-left:auto;">{{ $activeDevices }}</span>
                @endif
            </a>
            <a href="{{ route('customers.index') }}" class="nav-subitem {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Customers
            </a>
            <a href="{{ route('backbone_links.index') }}" class="nav-subitem {{ request()->routeIs('backbone_links.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                Backbone Links
            </a>
            <a href="{{ route('device_types.index') }}" class="nav-subitem {{ request()->routeIs('device_types.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Device Types
            </a>
            <a href="{{ route('service_types.index') }}" class="nav-subitem {{ request()->routeIs('service_types.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Service Types
            </a>
            <a href="{{ route('upstreams.index') }}" class="nav-subitem {{ request()->routeIs('upstreams.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                Upstreams
            </a>
            <a href="{{ route('providers.index') }}" class="nav-subitem {{ request()->routeIs('providers.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Providers / NAPs
            </a>
        </div>
    </div>
    @endif

    {{-- Monitoring --}}
    @if($canMonitor)
    <div x-data="{ open: {{ $monitorActive ? 'true' : 'false' }} }">
        <button @click="open = !open" class="nav-group-btn">
            <span style="display:flex;align-items:center;gap:10px;">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Monitoring
            </span>
            <svg :class="open ? 'rotated' : ''" class="chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-collapse>
            <a href="{{ route('device_reports.index') }}" class="nav-subitem {{ request()->routeIs('device_reports.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Device Monthly Report
            </a>
            <a href="{{ route('backbone_incidents.index') }}" class="nav-subitem {{ request()->routeIs('backbone_incidents.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Backbone Monitoring
            </a>
            <a href="{{ route('upstream_reports.index') }}" class="nav-subitem {{ request()->routeIs('upstream_reports.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Upstream Monitoring
            </a>
        </div>
    </div>
    @endif

    {{-- Incident --}}
    @if($canIncident)
    <div x-data="{ open: {{ $incidentActive ? 'true' : 'false' }} }">
        <button @click="open = !open" class="nav-group-btn">
            <span style="display:flex;align-items:center;gap:10px;">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Incident
            </span>
            <span style="display:flex;align-items:center;gap:6px;">
                @if($openIncidents > 0)
                    <span class="badge badge-red">{{ $openIncidents }}</span>
                @endif
                <svg :class="open ? 'rotated' : ''" class="chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </span>
        </button>
        <div x-show="open" x-collapse>
            <a href="{{ route('customer_incidents.index') }}" class="nav-subitem {{ request()->routeIs('customer_incidents.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Customer Incident
                @if($openIncidents > 0)
                    <span class="badge badge-red" style="margin-left:auto;">{{ $openIncidents }}</span>
                @endif
            </a>
        </div>
    </div>
    @endif

    {{-- Service Management --}}
    @if($canService)
    <div x-data="{ open: {{ $serviceActive ? 'true' : 'false' }} }">
        <button @click="open = !open" class="nav-group-btn">
            <span style="display:flex;align-items:center;gap:10px;">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Service Mgmt
            </span>
            <svg :class="open ? 'rotated' : ''" class="chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-collapse>
            <a href="{{ route('service_logs.index') }}" class="nav-subitem {{ request()->routeIs('service_logs.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Customer Service Log
            </a>
            <a href="{{ route('backbone_logs.index') }}" class="nav-subitem {{ request()->routeIs('backbone_logs.*') ? 'active' : '' }}">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Backbone Capacity
            </a>
        </div>
    </div>
    @endif

    {{-- Reports --}}
    @if($canReports)
    <div x-data="{ open: false }">
        <button @click="open = !open" class="nav-group-btn">
            <span style="display:flex;align-items:center;gap:10px;">
                <svg class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Reports
            </span>
            <svg :class="open ? 'rotated' : ''" class="chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open" x-collapse>
            <a href="{{ route('dashboard') }}" class="nav-subitem">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Monthly Summary
            </a>
            <a href="{{ route('dashboard') }}" class="nav-subitem">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                SLA Report
            </a>
            <a href="{{ route('dashboard.export') }}" class="nav-subitem">
                <svg class="nav-sub-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Export PDF
            </a>
        </div>
    </div>
    @endif

</nav>

{{-- Bottom logout --}}
<div style="padding:12px 16px;border-top:1px solid #1f2937;background:#030712;flex-shrink:0;">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" style="display:flex;align-items:center;gap:8px;background:none;border:none;cursor:pointer;font-size:.8125rem;color:#9ca3af;width:100%;text-align:left;padding:6px 0;transition:color .15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#9ca3af'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </button>
    </form>
</div>

<script>
    // Show close button on mobile
    (function() {
        var btn = document.getElementById('sidebar-close-btn');
        if (btn) {
            var update = function() { btn.style.display = window.innerWidth <= 768 ? 'block' : 'none'; };
            update(); window.addEventListener('resize', update);
        }
    })();
</script>
