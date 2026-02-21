<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'NOC Reporting') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Alpine.js collapse plugin (for sidebar groups) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- App styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Layout shell */
        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .app-sidebar {
            width: 256px;
            min-width: 256px;
            background: #111827;
            color: #fff;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        .app-sidebar::-webkit-scrollbar { width: 4px; }
        .app-sidebar::-webkit-scrollbar-track { background: #111827; }
        .app-sidebar::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }

        /* Main content */
        .app-main {
            flex: 1;
            margin-left: 256px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f3f4f6;
        }

        /* Topbar */
        .app-topbar {
            height: 56px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.25rem;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        /* Mobile overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 35;
        }

        /* Mobile: collapse sidebar */
        @media (max-width: 768px) {
            .app-sidebar { transform: translateX(-100%); }
            .app-sidebar.sidebar-open { transform: translateX(0); }
            .sidebar-overlay.sidebar-open { display: block; }
            .app-main { margin-left: 0; }
            .hamburger-btn { display: flex; }
        }
        @media (min-width: 769px) {
            .hamburger-btn { display: none; }
        }

        /* Sidebar nav styles */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: 0.875rem;
            color: #d1d5db;
            text-decoration: none;
            border-radius: 6px;
            margin: 1px 8px;
            transition: background 0.15s, color 0.15s;
        }
        .nav-item:hover { background: #374151; color: #fff; }
        .nav-item.active { background: #2563eb; color: #fff; font-weight: 500; }

        .nav-group-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 9px 16px;
            font-size: 0.875rem;
            color: #d1d5db;
            background: none;
            border: none;
            cursor: pointer;
            transition: background 0.15s, color 0.15s;
            text-align: left;
        }
        .nav-group-btn:hover { background: #374151; color: #fff; }

        .nav-subitem {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px 7px 20px;
            font-size: 0.8125rem;
            color: #9ca3af;
            text-decoration: none;
            border-radius: 6px;
            margin: 1px 8px 1px 16px;
            transition: background 0.15s, color 0.15s;
        }
        .nav-subitem:hover { background: #374151; color: #fff; }
        .nav-subitem.active { background: #1d4ed8; color: #fff; font-weight: 500; }

        .badge { display: inline-flex; align-items: center; justify-content: center; padding: 1px 6px; font-size: 0.7rem; font-weight: 700; border-radius: 9999px; }
        .badge-red { background: #ef4444; color: #fff; }
        .badge-blue { background: #3b82f6; color: #fff; }

        .chevron { width: 14px; height: 14px; transition: transform 0.2s; }
        .chevron.rotated { transform: rotate(180deg); }

        .nav-icon { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-sub-icon { width: 15px; height: 15px; flex-shrink: 0; color: #6b7280; }

        /* Flash messages */
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 10px 14px; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
        .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 10px 14px; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; }
    </style>
</head>
<body style="margin:0; background:#f3f4f6;">

    {{-- Sidebar Overlay (mobile) --}}
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

    <div class="app-shell">

        {{-- ===== SIDEBAR ===== --}}
        <aside id="app-sidebar" class="app-sidebar">
            @include('layouts.sidebar')
        </aside>

        {{-- ===== MAIN AREA ===== --}}
        <div class="app-main">

            {{-- Topbar --}}
            <header class="app-topbar">
                <div style="display:flex;align-items:center;gap:12px;">
                    {{-- Hamburger mobile --}}
                    <button onclick="openSidebar()" class="hamburger-btn" style="background:none;border:none;cursor:pointer;padding:4px;border-radius:6px;color:#6b7280;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Heading --}}
                    @isset($header)
                        <div>{{ $header }}</div>
                    @else
                        <span style="font-size:.875rem;font-weight:600;color:#374151;">{{ config('app.name') }}</span>
                    @endisset
                </div>

                {{-- Right actions --}}
                <div style="display:flex;align-items:center;gap:10px;">
                    @php $openCount = \App\Models\CustomerIncident::where('status','open')->count(); @endphp
                    @if($openCount > 0)
                    <a href="{{ route('customer_incidents.index') }}" style="position:relative;padding:6px;border-radius:50%;color:#6b7280;text-decoration:none;display:flex;align-items:center;" title="{{ $openCount }} open incidents">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span style="position:absolute;top:2px;right:2px;width:15px;height:15px;background:#ef4444;color:#fff;font-size:.6rem;font-weight:700;border-radius:50%;display:flex;align-items:center;justify-content:center;">{{ $openCount > 9 ? '9+' : $openCount }}</span>
                    </a>
                    @endif

                    {{-- User dropdown --}}
                    <div x-data="{ open: false }" style="position:relative;">
                        <button @click="open = !open" style="display:flex;align-items:center;gap:8px;background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:20px;font-size:.875rem;color:#374151;">
                            <div style="width:28px;height:28px;background:#2563eb;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;flex-shrink:0;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:500;max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Auth::user()->name }}</span>
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            style="position:absolute;right:0;top:calc(100% + 6px);width:180px;background:#fff;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,.12);border:1px solid #f3f4f6;z-index:50;padding:4px 0;">
                            <div style="padding:10px 14px;border-bottom:1px solid #f3f4f6;">
                                <p style="font-size:.875rem;font-weight:600;color:#111827;">{{ Auth::user()->name }}</p>
                                <p style="font-size:.75rem;color:#6b7280;text-transform:capitalize;">{{ Auth::user()->role ?? 'user' }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" style="display:flex;align-items:center;gap:8px;padding:9px 14px;font-size:.875rem;color:#374151;text-decoration:none;">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="display:flex;align-items:center;gap:8px;width:100%;padding:9px 14px;font-size:.875rem;color:#dc2626;background:none;border:none;cursor:pointer;text-align:left;">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Optional page heading slot --}}
            @isset($pageHeader)
                <div style="background:#fff;border-bottom:1px solid #e5e7eb;padding:14px 20px;">
                    {{ $pageHeader }}
                </div>
            @endisset

            {{-- Main content --}}
            <main style="flex:1;padding:20px;">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="flash-success">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="flash-error">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer style="padding:12px 20px;text-align:center;font-size:.75rem;color:#9ca3af;border-top:1px solid #e5e7eb;background:#fff;">
                © {{ date('Y') }} {{ config('app.name', 'NOC Reporting') }} — Built with Laravel 12
            </footer>
        </div>
    </div>

    {{-- Sidebar toggle script --}}
    <script>
        function openSidebar() {
            document.getElementById('app-sidebar').classList.add('sidebar-open');
            document.getElementById('sidebar-overlay').classList.add('sidebar-open');
        }
        function closeSidebar() {
            document.getElementById('app-sidebar').classList.remove('sidebar-open');
            document.getElementById('sidebar-overlay').classList.remove('sidebar-open');
        }
    </script>

    @stack('scripts')
</body>
</html>
