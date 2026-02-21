<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use App\Models\ServiceLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceManagementRBACTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_service_logs_index()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('service_logs.index'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_create_service_log()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('service_logs.create'));
        $response->assertStatus(403);
    }

    public function test_noc_can_create_service_log_and_update_bandwidth()
    {
        $noc = User::factory()->create(['role' => 'noc']);
        $customer = Customer::create([
             'name' => 'Test Customer Bandwidth',
             'service_type' => 'dedicated',
             'bandwidth' => '50 Mbps', 
             'status' => 'active'
        ]);

        $response = $this->actingAs($noc)->post(route('service_logs.store'), [
            'customer_id' => $customer->id,
            'type' => 'upgrade',
            'old_bandwidth' => '50 Mbps',
            'new_bandwidth' => '100 Mbps',
            'request_date' => now()->toDateString(),
            'notes' => 'Upgrade request',
        ]);

        $response->assertRedirect(route('service_logs.index'));
        
        $this->assertDatabaseHas('service_logs', ['new_bandwidth' => '100 Mbps']);
        
        // Refresh customer to check if bandwidth was updated
        $customer->refresh();
        $this->assertEquals('100 Mbps', $customer->bandwidth);
    }

    public function test_activation_log_does_not_require_old_bandwidth()
    {
        $noc = User::factory()->create(['role' => 'noc']);
        $customer = Customer::create([
             'name' => 'New Customer',
             'service_type' => 'broadband',
             'bandwidth' => '10 Mbps', 
             'status' => 'active'
        ]);

        $response = $this->actingAs($noc)->post(route('service_logs.store'), [
            'customer_id' => $customer->id,
            'type' => 'activation',
            'new_bandwidth' => '10 Mbps',
            'request_date' => now()->toDateString(),
            'notes' => 'Activation',
        ]);

        $response->assertRedirect(route('service_logs.index'));
    }
}
