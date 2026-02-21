<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Customer;
use App\Models\CustomerIncident;

class CustomerIncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();

        foreach ($customers as $customer) {
            for ($i = 0; $i < 5; $i++) {
                CustomerIncident::create([
                    'customer_id' => $customer->id,
                    'incident_date' => now()->subDays(rand(1, 60))->subHours(rand(0, 23)),
                    'physical_issue' => (bool)rand(0, 1),
                    'backbone_issue' => (bool)rand(0, 1),
                    'layer_issue' => rand(0, 1) ? 'Layer 2' : 'Layer 3',
                    'duration' => rand(10, 300), // minutes
                    'root_cause' => 'Fiber cut or power outage',
                    'status' => rand(0, 1) ? 'open' : 'closed',
                    'notes' => 'Generated incident',
                ]);
            }
        }
    }
}
