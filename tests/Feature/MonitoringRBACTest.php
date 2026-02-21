<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\DeviceReport;
use App\Models\Device;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MonitoringRBACTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_device_reports_index()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('device_reports.index'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_create_device_report()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('device_reports.create'));
        $response->assertStatus(403);
    }

    public function test_noc_can_view_create_page()
    {
        $noc = User::factory()->create(['role' => 'noc']);
        $response = $this->actingAs($noc)->get(route('device_reports.create'));
        $response->assertStatus(200);
    }

    public function test_filter_device_reports_by_month()
    {
        $noc = User::factory()->create(['role' => 'noc']);
        
        $location = \App\Models\Location::create([
             'name' => 'Test POP',
             'address' => 'Test Address', 
             'type' => 'POP'
        ]);

        $device = Device::create([
            'name' => 'Test Device',
            'type' => 'router',
            'brand' => 'Cisco',
            'model' => '2960',
            'serial_number' => '12345',
            'status' => 'active',
            'location_id' => $location->id,
        ]);

        DeviceReport::create([
            'device_id' => $device->id,
            'month' => '2025-01-01',
            'physical_status' => 'Good',
            'psu_status' => 'Normal',
            'fan_status' => 'Normal',
            'layer2_status' => 'Up',
            'layer3_status' => 'Up',
            'cpu_status' => '10%',
            'throughput_in' => '100 Mbps',
            'throughput_out' => '100 Mbps',
            'duration_downtime' => 0,
            'notes' => 'Test',
        ]);

        DeviceReport::create([
            'device_id' => $device->id,
            'month' => '2025-02-01',
            'physical_status' => 'Good',
            'psu_status' => 'Normal',
            'fan_status' => 'Normal',
            'layer2_status' => 'Up',
            'layer3_status' => 'Up',
            'cpu_status' => '10%',
            'throughput_in' => '100 Mbps',
            'throughput_out' => '100 Mbps',
            'duration_downtime' => 0,
            'notes' => 'Test',
        ]);

        $response = $this->actingAs($noc)->get(route('device_reports.index', ['month' => '2025-01']));
        $response->assertStatus(200);
        
        $response->assertViewHas('reports', function ($reports) {
            return $reports->count() === 1 
                && $reports->first()->month->format('Y-m-d') === '2025-01-01';
        });
    }
}
