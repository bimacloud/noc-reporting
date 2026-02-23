<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-500">
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedDate)->format('F Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <label for="date" class="text-sm font-medium text-gray-600">Period:</label>
                    <input type="month" id="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                           class="border-gray-300 rounded-lg text-sm focus:ring-red-500 focus:border-red-500 py-1.5 px-3">
                    
                    @if(request('incident_filter'))
                        <input type="hidden" name="incident_filter" value="{{ request('incident_filter') }}">
                    @endif
                    @if(request('activation_filter'))
                        <input type="hidden" name="activation_filter" value="{{ request('activation_filter') }}">
                    @endif
                </form>

                <a href="{{ route('dashboard.export', ['date' => $selectedDate]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Stats Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">

        {{-- Incidents --}}
        <a href="{{ route('customer_incidents.index') }}" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #f3f4f6;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .15s,transform .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.06)';this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:#fee2e2;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:24px;height:24px;color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px;">Total Incidents</p>
                <p style="font-size:1.75rem;font-weight:700;color:#dc2626;margin:0;line-height:1.1;">{{ $totalIncidents }}</p>
                <p style="font-size:.7rem;color:#d1d5db;margin:2px 0 0;">This month →</p>
            </div>
        </a>

        {{-- Downtime --}}
        <a href="{{ route('backbone_incidents.index') }}" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #f3f4f6;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .15s,transform .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.06)';this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:#ffedd5;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:24px;height:24px;color:#f97316;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px;">Total Downtime</p>
                <p style="font-size:1.75rem;font-weight:700;color:#ea580c;margin:0;line-height:1.1;">
                    @if($totalDowntime >= 60){{ floor($totalDowntime/60) }}h {{ $totalDowntime%60 }}m
                    @else{{ $totalDowntime }}m
                    @endif
                </p>
                <p style="font-size:.7rem;color:#d1d5db;margin:2px 0 0;">This month →</p>
            </div>
        </a>

        {{-- SLA --}}
        @php
            $slaColor  = $slaPercentage >= 99 ? '#16a34a' : ($slaPercentage >= 95 ? '#ca8a04' : '#dc2626');
            $slaBg     = $slaPercentage >= 99 ? '#dcfce7' : ($slaPercentage >= 95 ? '#fef9c3' : '#fee2e2');
        @endphp
        <a href="{{ route('device_reports.index') }}" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #f3f4f6;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .15s,transform .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.06)';this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:{{ $slaBg }};border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:24px;height:24px;color:{{ $slaColor }};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px;">SLA</p>
                <p style="font-size:1.75rem;font-weight:700;color:{{ $slaColor }};margin:0;line-height:1.1;">{{ number_format($slaPercentage, 2) }}%</p>
                <p style="font-size:.7rem;color:#d1d5db;margin:2px 0 0;">Uptime this month →</p>
            </div>
        </a>

        {{-- New Activations --}}
        <a href="{{ route('service_logs.index') }}" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #f3f4f6;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .15s,transform .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.06)';this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:24px;height:24px;color:#3b82f6;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px;">Customer Activations</p>
                <p style="font-size:1.75rem;font-weight:700;color:#2563eb;margin:0;line-height:1.1;">{{ $totalActivations }}</p>
                <p style="font-size:.7rem;color:#d1d5db;margin:2px 0 0;">This month →</p>
            </div>
        </a>

        {{-- Upgrades --}}
        <a href="{{ route('service_logs.index') }}" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #f3f4f6;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .15s,transform .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.06)';this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:#cffafe;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:24px;height:24px;color:#0891b2;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px;">Service Upgrades</p>
                <p style="font-size:1.75rem;font-weight:700;color:#0e7490;margin:0;line-height:1.1;">{{ $totalUpgrades }}</p>
                <p style="font-size:.7rem;color:#d1d5db;margin:2px 0 0;">This month →</p>
            </div>
        </a>

        {{-- Downgrades --}}
        <a href="{{ route('service_logs.index') }}" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #f3f4f6;box-shadow:0 1px 3px rgba(0,0,0,.06);display:flex;align-items:center;gap:16px;text-decoration:none;transition:box-shadow .15s,transform .15s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,.06)';this.style.transform='translateY(0)'">
            <div style="width:48px;height:48px;background:#f3f4f6;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:24px;height:24px;color:#4b5563;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
            <div>
                <p style="font-size:.7rem;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px;">Service Downgrades</p>
                <p style="font-size:1.75rem;font-weight:700;color:#374151;margin:0;line-height:1.1;">{{ $totalDowngrades }}</p>
                <p style="font-size:.7rem;color:#d1d5db;margin:2px 0 0;">This month →</p>
            </div>
        </a>

    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    Incidents (Customer & Backbone)
                </h3>
                <form method="GET" action="{{ route('dashboard') }}">
                    <input type="hidden" name="date" value="{{ $selectedDate }}">
                    <input type="hidden" name="activation_filter" value="{{ $activationFilter }}">
                    <select name="incident_filter" onchange="this.form.submit()" class="text-xs border-gray-300 rounded focus:ring-red-500 focus:border-red-500">
                        <option value="daily" {{ $incidentFilter === 'daily' ? 'selected' : '' }}>Harian (Bulan Ini)</option>
                        <option value="weekly" {{ $incidentFilter === 'weekly' ? 'selected' : '' }}>Mingguan (Bulan Ini)</option>
                        <option value="monthly" {{ $incidentFilter === 'monthly' ? 'selected' : '' }}>Bulanan (Tahun Ini)</option>
                        <option value="yearly" {{ $incidentFilter === 'yearly' ? 'selected' : '' }}>Tahunan (5 Tahun Terakhir)</option>
                    </select>
                </form>
            </div>
            <div style="position:relative;height:220px;">
                <canvas id="incidentsChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Service Changes (Act, Upg, Dwn)
                </h3>
                <form method="GET" action="{{ route('dashboard') }}">
                    <input type="hidden" name="date" value="{{ $selectedDate }}">
                    <input type="hidden" name="incident_filter" value="{{ $incidentFilter }}">
                    <select name="activation_filter" onchange="this.form.submit()" class="text-xs border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="daily" {{ $activationFilter === 'daily' ? 'selected' : '' }}>Harian (Bulan Ini)</option>
                        <option value="weekly" {{ $activationFilter === 'weekly' ? 'selected' : '' }}>Mingguan (Bulan Ini)</option>
                        <option value="monthly" {{ $activationFilter === 'monthly' ? 'selected' : '' }}>Bulanan (Tahun Ini)</option>
                        <option value="yearly" {{ $activationFilter === 'yearly' ? 'selected' : '' }}>Tahunan (5 Tahun Terakhir)</option>
                    </select>
                </form>
            </div>
            <div style="position:relative;height:220px;">
                <canvas id="activationsChart"></canvas>
            </div>
        </div>

    </div>

    {{-- Downtime Per Device --}}
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
        <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18" />
            </svg>
            Top 5 Devices by Downtime (This Month)
        </h3>
        @if($downtimePerDevice->isEmpty())
            <div class="text-center py-10 text-gray-400 text-sm">
                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                No device downtime data for this month.
            </div>
        @else
            <canvas id="downtimeChart" height="100"></canvas>
        @endif
    </div>

    {{-- NetBox Synchronization — Admin Only --}}
    @if(auth()->user()->isAdmin())
    <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 mt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center gap-2">
            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            NetBox Synchronization
        </h3>
        <p class="text-xs text-gray-400 mb-1">Urutan sync: <strong>Site Groups → Sites → Locations → Devices</strong></p>
        <div class="flex gap-4 text-xs text-gray-400 mb-4">
            <span>① Site Groups = Daftar DC/POP</span>
            <span>② Sites = Wilayah dalam DC/POP</span>
            <span>③ Locations = Lokasi fisik dalam Site</span>
            <span>④ Devices = Perangkat</span>
        </div>

        <div class="flex flex-wrap gap-3" x-data="{ loading: null }">

            {{-- 1. Sync Site Groups --}}
            <form method="POST" action="{{ route('netbox.sync.site-groups') }}" @submit="loading = 'sg'">
                @csrf
                <button type="submit" :disabled="loading !== null"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition">
                    <template x-if="loading === 'sg'">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </template>
                    <template x-if="loading !== 'sg'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </template>
                    <span x-text="loading === 'sg' ? 'Syncing...' : '① Sync Site Groups'"></span>
                </button>
            </form>

            {{-- 2. Sync Sites --}}
            <form method="POST" action="{{ route('netbox.sync.sites') }}" @submit="loading = 'sites'">
                @csrf
                <button type="submit" :disabled="loading !== null"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition">
                    <template x-if="loading === 'sites'">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </template>
                    <template x-if="loading !== 'sites'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    </template>
                    <span x-text="loading === 'sites' ? 'Syncing...' : '② Sync Sites'"></span>
                </button>
            </form>

            {{-- 3. Sync Locations --}}
            <form method="POST" action="{{ route('netbox.sync.locations') }}" @submit="loading = 'locations'">
                @csrf
                <button type="submit" :disabled="loading !== null"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-500 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition">
                    <template x-if="loading === 'locations'">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </template>
                    <template x-if="loading !== 'locations'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </template>
                    <span x-text="loading === 'locations' ? 'Syncing...' : '③ Sync Locations'"></span>
                </button>
            </form>

            {{-- 4. Sync Devices --}}
            <form method="POST" action="{{ route('netbox.sync.devices') }}" @submit="loading = 'devices'">
                @csrf
                <button type="submit" :disabled="loading !== null"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-60 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition">
                    <template x-if="loading === 'devices'">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    </template>
                    <template x-if="loading !== 'devices'">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/></svg>
                    </template>
                    <span x-text="loading === 'devices' ? 'Syncing...' : '④ Sync Devices'"></span>
                </button>
            </form>

        </div>
    </div>
    @endif

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Incidents Chart
        const incidentsData = @json($incidentsChartData);
        new Chart(document.getElementById('incidentsChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(incidentsData).length ? Object.keys(incidentsData) : ['No data'],
                datasets: [{
                    label: 'Incidents',
                    data: Object.values(incidentsData).length ? Object.values(incidentsData) : [0],
                    backgroundColor: 'rgba(239, 68, 68, 0.6)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1, borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f3f4f6' } }, x: { grid: { display: false } } }
            }
        });

        // Service Changes Pie Chart
        const pAct = {{ $pieActivations ?? 0 }};
        const pUpg = {{ $pieUpgrades ?? 0 }};
        const pDwn = {{ $pieDowngrades ?? 0 }};

        new Chart(document.getElementById('activationsChart'), {
            type: 'doughnut',
            data: {
                labels: ['Activations', 'Upgrades', 'Downgrades'],
                datasets: [{
                    data: [pAct, pUpg, pDwn],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)', // blue
                        'rgba(8, 145, 178, 0.8)',  // cyan
                        'rgba(107, 114, 128, 0.8)' // gray
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: { 
                    legend: { 
                        display: true, 
                        position: 'right', 
                        labels: { boxWidth: 12, usePointStyle: true, font: { size: 12 } } 
                    } 
                }
            }
        });

        // Downtime per Device
        @if($downtimePerDevice->isNotEmpty())
        const dtData = @json($downtimePerDevice);
        new Chart(document.getElementById('downtimeChart'), {
            type: 'bar',
            data: {
                labels: dtData.map(d => d.device),
                datasets: [{
                    label: 'Downtime (minutes)',
                    data: dtData.map(d => d.downtime),
                    backgroundColor: 'rgba(249, 115, 22, 0.6)',
                    borderColor: 'rgba(249, 115, 22, 1)',
                    borderWidth: 1, borderRadius: 4
                }]
            },
            options: {
                responsive: true, indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, grid: { color: '#f3f4f6' } }, y: { grid: { display: false } } }
            }
        });
        @endif
    </script>
    @endpush
</x-app-layout>
