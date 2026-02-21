<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Customer;
use App\Models\ServiceLog;

class ServiceLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            for ($i = 0; $i < 3; $i++) {
                $type = ['activation', 'deactivation', 'upgrade', 'downgrade'][rand(0, 3)];
                ServiceLog::create([
                    'customer_id' => $customer->id,
                    'type' => $type,
                    'old_bandwidth' => $type === 'activation' ? null : $customer->bandwidth,
                    'new_bandwidth' => in_array($type, ['upgrade', 'downgrade', 'activation']) ? rand(10, 100) . ' Mbps' : null,
                    'request_date' => now()->subDays(rand(1, 60)),
                    'execute_date' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                    'notes' => ucfirst($type) . ' request',
                ]);
            }
        }
    }
}
