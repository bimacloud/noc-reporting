<?php

namespace App\Http\Controllers;

use App\Models\SiteGroup;
use App\Models\Site;
use App\Models\Location;
use App\Models\Device;
use App\Models\NetboxSyncLog;
use Illuminate\Support\Facades\Log;

class NetboxSyncController extends Controller
{
    private string $baseUrl;
    private string $token;
    private string $batchId;

    public function __construct()
    {
        $this->baseUrl  = rtrim(env('NETBOX_URL', ''), '/');
        $this->token    = env('NETBOX_TOKEN', '');
        $this->batchId  = now()->format('YmdHis');
    }

    /**
     * cURL helper: GET ke NetBox, kembalikan array results atau false.
     */
    private function netboxGet(string $endpoint): array|false
    {
        $url = $this->baseUrl . $endpoint . '?limit=1000';

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Token ' . $this->token,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $body   = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error  = curl_error($ch);
        curl_close($ch);

        if ($error) {
            Log::error("NetBox cURL error [{$endpoint}]: {$error}");
            return false;
        }

        if ($status !== 200) {
            Log::error("NetBox HTTP {$status} on [{$endpoint}]");
            return false;
        }

        $json = json_decode($body, true);
        return is_array($json['results'] ?? null) ? $json['results'] : false;
    }

    /**
     * Catat satu baris log ke tabel netbox_sync_logs.
     */
    private function log(string $type, $item, string $status, string $message = '', bool $storePayload = false): void
    {
        NetboxSyncLog::create([
            'entity_type' => $type,
            'netbox_id'   => $item['id'] ?? null,
            'entity_name' => $item['name'] ?? null,
            'status'      => $status,
            'message'     => $message,
            'payload'     => ($status === 'error' || $storePayload)
                             ? $item
                             : null,
            'synced_at'   => now(),
        ]);
    }

    // ================================================================
    // 1. SYNC SITE GROUPS
    // ================================================================
    public function syncSiteGroups()
    {
        $results = $this->netboxGet('/dcim/site-groups/');

        if ($results === false) {
            return redirect()->back()->with('error', 'NetBox API gagal diakses (site-groups). Cek laravel.log.');
        }

        usort($results, fn($a, $b) => ($a['_depth'] ?? 0) <=> ($b['_depth'] ?? 0));

        $ok = $errors = 0;
        foreach ($results as $item) {
            try {
                $parentLocalId = null;
                if (!empty($item['parent']['id'])) {
                    $parentSg      = SiteGroup::where('netbox_id', $item['parent']['id'])->first();
                    $parentLocalId = $parentSg?->id;
                }

                SiteGroup::updateOrCreate(
                    ['netbox_id' => $item['id']],
                    [
                        'name'        => $item['name'],
                        'slug'        => $item['slug']         ?? null,
                        'parent_id'   => $parentLocalId,
                        'parent_name' => $item['parent']['name'] ?? null,
                        'description' => $item['description']  ?? null,
                        'depth'       => $item['_depth']       ?? 0,
                        'site_count'  => $item['site_count']   ?? 0,
                    ]
                );
                $this->log('site_groups', $item, 'ok', 'Synced OK');
                $ok++;
            } catch (\Exception $e) {
                $this->log('site_groups', $item, 'error', $e->getMessage());
                Log::error('SiteGroup sync error: ' . $e->getMessage(), ['item' => $item]);
                $errors++;
            }
        }

        $msg = "✅ Site Groups: {$ok} berhasil" . ($errors ? ", {$errors} gagal" : '') . '.';
        return redirect()->back()->with($errors ? 'warning' : 'success', $msg);
    }

    // ================================================================
    // 2. SYNC SITES
    // ================================================================
    public function syncSites()
    {
        $results = $this->netboxGet('/dcim/sites/');

        if ($results === false) {
            return redirect()->back()->with('error', 'NetBox API gagal diakses (sites). Cek laravel.log.');
        }

        $ok = $errors = 0;
        foreach ($results as $item) {
            try {
                $siteGroupLocalId = null;
                if (!empty($item['group']['id'])) {
                    $sg = SiteGroup::where('netbox_id', $item['group']['id'])->first();
                    $siteGroupLocalId = $sg?->id;
                }

                Site::updateOrCreate(
                    ['netbox_id' => $item['id']],
                    [
                        'name'            => $item['name'],
                        'slug'            => $item['slug']              ?? null,
                        'status'          => $item['status']['value']   ?? 'active',
                        'region'          => $item['region']['name']    ?? null,
                        'site_group_id'   => $siteGroupLocalId,
                        'site_group_name' => $item['group']['name']     ?? null,
                        'address'         => $item['physical_address']  ?? null,
                        'description'     => $item['description']       ?? null,
                        'device_count'    => $item['device_count']      ?? 0,
                    ]
                );
                $this->log('sites', $item, 'ok', 'Synced OK');
                $ok++;
            } catch (\Exception $e) {
                $this->log('sites', $item, 'error', $e->getMessage());
                Log::error('Site sync error: ' . $e->getMessage(), ['item' => $item]);
                $errors++;
            }
        }

        $msg = "✅ Sites: {$ok} berhasil" . ($errors ? ", {$errors} gagal" : '') . '.';
        return redirect()->back()->with($errors ? 'warning' : 'success', $msg);
    }

    // ================================================================
    // 3. SYNC LOCATIONS
    // ================================================================
    public function syncLocations()
    {
        $results = $this->netboxGet('/dcim/locations/');

        if ($results === false) {
            return redirect()->back()->with('error', 'NetBox API gagal diakses (locations). Cek laravel.log.');
        }

        $ok = $errors = 0;
        foreach ($results as $item) {
            try {
                $siteLocalId = null;
                if (!empty($item['site']['id'])) {
                    $site        = Site::where('netbox_id', $item['site']['id'])->first();
                    $siteLocalId = $site?->id;
                }

                Location::updateOrCreate(
                    ['netbox_id' => $item['id']],
                    [
                        'name'         => $item['name'],
                        'slug'         => $item['slug']            ?? null,
                        'type'         => 'location',
                        'status'       => $item['status']['value'] ?? 'active',
                        'description'  => $item['description']     ?? null,
                        'tenant'       => $item['tenant']['name']  ?? null,
                        'device_count' => $item['device_count']    ?? 0,
                        'site_id'      => $siteLocalId,
                        'site_name'    => $item['site']['name']    ?? null,
                    ]
                );
                $this->log('locations', $item, 'ok', 'Synced OK');
                $ok++;
            } catch (\Exception $e) {
                $this->log('locations', $item, 'error', $e->getMessage());
                Log::error('Location sync error: ' . $e->getMessage(), ['item' => $item]);
                $errors++;
            }
        }

        $msg = "✅ Locations: {$ok} berhasil" . ($errors ? ", {$errors} gagal" : '') . '.';
        return redirect()->back()->with($errors ? 'warning' : 'success', $msg);
    }

    // ================================================================
    // 4. SYNC DEVICES
    // ================================================================
    public function syncDevices()
    {
        $results = $this->netboxGet('/dcim/devices/');

        if ($results === false) {
            return redirect()->back()->with('error', 'NetBox API gagal diakses (devices). Cek laravel.log.');
        }

        $ok = $errors = $skipped = 0;
        foreach ($results as $item) {
            try {
                // Resolve site_id
                $siteLocalId = null;
                if (!empty($item['site']['id'])) {
                    $site        = Site::where('netbox_id', $item['site']['id'])->first();
                    $siteLocalId = $site?->id;
                }

                // Resolve location_id — nullable, no longer throws
                $locationLocalId = null;
                if (!empty($item['location']['id'])) {
                    $loc             = Location::where('netbox_id', $item['location']['id'])->first();
                    $locationLocalId = $loc?->id;
                }

                // Parse IP (strip /24 CIDR)
                $rawIp  = $item['primary_ip4']['address'] ?? null;
                $ipAddr = $rawIp ? explode('/', $rawIp)[0] : null;

                $brand = $item['device_type']['manufacturer']['name'] ?? null;
                $model = $item['device_type']['model'] ?? null;

                Device::updateOrCreate(
                    ['netbox_id' => $item['id']],
                    [
                        'name'          => $item['name'],
                        'ip_address'    => $ipAddr,
                        'brand'         => $brand,
                        'manufacturer'  => $brand,
                        'model'         => $model,
                        'serial_number' => $item['serial']              ?? null,
                        'platform'      => $item['platform']['name']    ?? null,
                        'role'          => $item['role']['name']        ?? null,
                        'site_id'       => $siteLocalId,
                        'site_name'     => $item['site']['name']        ?? null,
                        'location_id'   => $locationLocalId,
                        'status'        => ($item['status']['value'] ?? 'active') === 'active'
                                           ? 'active' : 'inactive',
                    ]
                );

                $note = $locationLocalId === null && !empty($item['location']['id'])
                    ? 'Synced OK (location not found locally — jalankan Sync Locations dulu)'
                    : 'Synced OK';

                $status = ($locationLocalId === null && !empty($item['location']['id']))
                    ? 'skipped'
                    : 'ok';

                $this->log('devices', $item, $status, $note);

                if ($status === 'ok') $ok++;
                else $skipped++;

            } catch (\Exception $e) {
                $this->log('devices', $item, 'error', $e->getMessage());
                Log::error('Device sync error [' . ($item['name'] ?? '?') . ']: ' . $e->getMessage());
                $errors++;
            }
        }

        $parts = ["✅ Devices: {$ok} berhasil"];
        if ($skipped) $parts[] = "{$skipped} partial (lokasi belum tersinkron)";
        if ($errors)  $parts[] = "{$errors} gagal";

        $level = $errors ? 'warning' : 'success';
        return redirect()->back()->with($level, implode(', ', $parts) . '.');
    }
}
