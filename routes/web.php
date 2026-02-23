<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\BackboneLogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\BackboneLinkController;
use App\Http\Controllers\UpstreamController;
use App\Http\Controllers\DeviceReportController;
use App\Http\Controllers\BackboneIncidentController;
use App\Http\Controllers\UpstreamReportController;
use App\Http\Controllers\CustomerIncidentController;
use App\Http\Controllers\ServiceLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\NetboxSyncController;
use App\Http\Controllers\SiteGroupController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\NetboxSyncLogController;
use App\Http\Controllers\ProviderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard/export', [DashboardController::class, 'exportPdf'])->middleware(['auth', 'verified'])->name('dashboard.export');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    // Master Data
    Route::resource('locations', LocationController::class);
    Route::resource('device_types', DeviceTypeController::class);
    Route::resource('service_types', ServiceTypeController::class);
    Route::resource('providers', ProviderController::class);
    Route::resource('devices', DeviceController::class);
    Route::get('/customers/import/template', [CustomerController::class, 'downloadTemplate'])->name('customers.template');
    Route::post('/customers/import', [CustomerController::class, 'import'])->name('customers.import');
    Route::resource('customers', CustomerController::class);
    Route::resource('backbone_links', BackboneLinkController::class);
    Route::resource('upstreams', UpstreamController::class);

    // NetBox Hierarchy — read-only (data synced from NetBox)
    Route::get('/site-groups', [SiteGroupController::class, 'index'])->name('site-groups.index');
    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');

    // Monitoring
    Route::resource('device_reports', DeviceReportController::class);
    Route::get('backbone_incidents/{incident}/resolve', [BackboneIncidentController::class, 'resolveForm'])->name('backbone_incidents.resolve_form');
    Route::put('backbone_incidents/{incident}/resolve', [BackboneIncidentController::class, 'resolve'])->name('backbone_incidents.resolve');
    Route::resource('backbone_incidents', BackboneIncidentController::class);
    Route::resource('upstream_reports', UpstreamReportController::class);
    Route::resource('customer_incidents', CustomerIncidentController::class);
    Route::resource('service_logs', ServiceLogController::class);
});

// NetBox Sync & Admin functions
Route::middleware(['auth','role:admin'])->prefix('admin')->group(function () {
    // User Management
    Route::resource('users', \App\Http\Controllers\UserController::class);

    Route::post('/sync/netbox/site-groups', [NetboxSyncController::class, 'syncSiteGroups'])
        ->name('netbox.sync.site-groups');

    Route::post('/sync/netbox/sites', [NetboxSyncController::class, 'syncSites'])
        ->name('netbox.sync.sites');

    Route::post('/sync/netbox/locations', [NetboxSyncController::class, 'syncLocations'])
        ->name('netbox.sync.locations');

    Route::post('/sync/netbox/devices', [NetboxSyncController::class, 'syncDevices'])
        ->name('netbox.sync.devices');

    // Sync Log Viewer
    Route::resource('service_logs', ServiceLogController::class);
    Route::resource('backbone_logs', BackboneLogController::class);
    Route::get('/sync/logs',   [NetboxSyncLogController::class, 'index'])->name('netbox.sync.logs');
    Route::delete('/sync/logs',[NetboxSyncLogController::class, 'destroy'])->name('netbox.sync.logs.clear');

});
