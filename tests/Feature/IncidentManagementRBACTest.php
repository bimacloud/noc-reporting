<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use App\Models\CustomerIncident;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncidentManagementRBACTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_incidents_index()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('customer_incidents.index'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_create_incident()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('customer_incidents.create'));
        $response->assertStatus(403);
    }

    public function test_noc_can_create_incident()
    {
        $noc = User::factory()->create(['role' => 'noc']);
        $customer = Customer::create([
             'name' => 'Test Customer',
             'service_type' => 'Dedicated',
             'bandwidth' => '100 Mbps', 
             'status' => 'active'
        ]);

        $response = $this->actingAs($noc)->post(route('customer_incidents.store'), [
            'customer_id' => $customer->id,
            'incident_date' => now()->subDay()->toDateString(),
            'physical_issue' => true,
            'backbone_issue' => false,
            'duration' => 60,
            'status' => 'open',
            'notes' => 'Test incident',
        ]);

        $response->assertRedirect(route('customer_incidents.index'));
        $this->assertDatabaseHas('customer_incidents', ['notes' => 'Test incident']);
    }

    public function test_avg_downtime_calculation()
    {
        $noc = User::factory()->create(['role' => 'noc']);
        $customer = Customer::create([
             'name' => 'Test Customer 2',
             'service_type' => 'Dedicated',
             'bandwidth' => '100 Mbps', 
             'status' => 'active'
        ]);

        CustomerIncident::create([
            'customer_id' => $customer->id,
            'incident_date' => '2025-01-01 10:00:00',
            'duration' => 60,
            'status' => 'closed',
        ]);

        CustomerIncident::create([
            'customer_id' => $customer->id,
            'incident_date' => '2025-01-02 10:00:00',
            'duration' => 30,
            'status' => 'closed',
        ]);

        $response = $this->actingAs($noc)->get(route('customer_incidents.index', ['customer_id' => $customer->id]));
        
        $response->assertViewHas('totalDowntime', 90);
    }
}
