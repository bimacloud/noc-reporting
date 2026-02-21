<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\ServiceType;
use App\Models\Customer;
use App\Models\Reseller;
use App\Models\BackboneLink;
use App\Models\Upstream;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Device Types
        $routerType = DeviceType::create(['name' => 'Router']);
        $switchType = DeviceType::create(['name' => 'Switch']);
        $oltType = DeviceType::create(['name' => 'OLT']);
        $serverType = DeviceType::create(['name' => 'Server']);
        $wirelessType = DeviceType::create(['name' => 'Wireless']);

        // Service Types
        $dedicated = ServiceType::create(['name' => 'Dedicated']);
        $broadband = ServiceType::create(['name' => 'Broadband']);
        $metro = ServiceType::create(['name' => 'Metro Ethernet']);

        // Locations
        $pop = Location::create(['name' => 'POP Main', 'address' => 'Jl. Sudirman No. 1', 'type' => 'POP']);
        $dc = Location::create(['name' => 'Data Center A', 'address' => 'Cyber Building', 'type' => 'DC']);
        $bts = Location::create(['name' => 'BTS Tower X', 'address' => 'Hilltop', 'type' => 'BTS']);

        // Devices
        Device::create(['name' => 'Router Core', 'device_type_id' => $routerType->id, 'brand' => 'MikroTik', 'model' => 'CCR1072', 'serial_number' => 'SN001', 'location_id' => $pop->id, 'status' => 'active']);
        Device::create(['name' => 'Switch Access', 'device_type_id' => $switchType->id, 'brand' => 'Cisco', 'model' => '2960', 'serial_number' => 'SN002', 'location_id' => $pop->id, 'status' => 'active']);
        Device::create(['name' => 'OLT 1', 'device_type_id' => $oltType->id, 'brand' => 'ZTE', 'model' => 'C320', 'serial_number' => 'SN003', 'location_id' => $dc->id, 'status' => 'active']);

        // Customers
        Customer::create(['name' => 'PT Maju Jaya', 'service_type_id' => $dedicated->id, 'bandwidth' => '100 Mbps', 'status' => 'active']);
        Customer::create(['name' => 'Cafe Relax', 'service_type_id' => $broadband->id, 'bandwidth' => '50 Mbps', 'status' => 'active']);

        // Resellers
        Reseller::create(['name' => 'Netlink', 'address' => 'Jl. Gatot Subroto', 'bandwidth' => '1 Gbps', 'status' => 'active']);

        // Backbone Links
        BackboneLink::create(['node_a' => 'POP Main', 'node_b' => 'Data Center A', 'provider' => 'Self', 'media' => 'FO', 'capacity' => '10 Gbps']);

        // Upstreams
        Upstream::create(['peer_name' => 'Telkom', 'capacity' => '10 Gbps', 'location_id' => $dc->id]);
    }
}
