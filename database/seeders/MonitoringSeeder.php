<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Device;
use App\Models\BackboneLink;
use App\Models\Upstream;
use App\Models\DeviceReport;
use App\Models\BackboneIncident;
use App\Models\UpstreamReport;

class MonitoringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Device Reports
        $devices = Device::all();
        foreach ($devices as $device) {
            for ($i = 0; $i < 3; $i++) {
                DeviceReport::create([
                    'device_id' => $device->id,
                    'month' => now()->subMonths($i)->startOfMonth()->toDateString(),
                    'physical_status' => 'Good',
                    'psu_status' => 'Normal',
                    'fan_status' => 'Normal',
                    'layer2_status' => 'Up',
                    'layer3_status' => 'Up',
                    'cpu_status' => rand(10, 50) . '%',
                    'throughput_in' => rand(100, 1000) . ' Mbps',
                    'throughput_out' => rand(100, 1000) . ' Mbps',
                    'duration_downtime' => rand(0, 100),
                    'notes' => 'Sample report',
                ]);
            }
        }

        // Backbone Incidents
        $links = BackboneLink::all();
        foreach ($links as $link) {
            for ($i = 0; $i < 3; $i++) {
                BackboneIncident::create([
                    'backbone_link_id' => $link->id,
                    'incident_date' => now()->subDays(rand(1, 60)),
                    'latency' => rand(5, 50) . 'ms',
                    'down_status' => (bool)rand(0, 1),
                    'duration' => rand(10, 120),
                    'notes' => 'Sample incident',
                ]);
            }
        }

        // Upstream Reports
        $upstreams = Upstream::all();
        foreach ($upstreams as $upstream) {
            for ($i = 0; $i < 3; $i++) {
                UpstreamReport::create([
                    'upstream_id' => $upstream->id,
                    'month' => now()->subMonths($i)->startOfMonth()->toDateString(),
                    'status_l1' => 'Up',
                    'status_l2' => 'Up',
                    'status_l3' => 'Up',
                    'advertise' => 'Accepted',
                    'duration' => rand(0, 50),
                    'notes' => 'Sample upstream report',
                ]);
            }
        }
    }
}
