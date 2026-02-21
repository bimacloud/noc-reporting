<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CustomerIncident;
use App\Models\ServiceLog;
use App\Models\MonthlySummary;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_screen_can_be_rendered()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_stats_are_calculated_correctly()
    {
        $user = User::factory()->create();
        $customer = Customer::create([
             'name' => 'Test Customer',
             'service_type' => 'metro',
             'bandwidth' => '10 Mbps', 
             'status' => 'active'
        ]);

        // Create incident for this month
        CustomerIncident::create([
            'customer_id' => $customer->id,
            'incident_date' => now(),
            'duration' => 60,
            'status' => 'closed',
            'notes' => 'Test incident',
            'physical_issue' => false,
            'backbone_issue' => false,
            'layer_issue' => 'Layer 3',
            'root_cause' => 'Config error'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewHas('totalIncidents', 1);
        $response->assertViewHas('totalDowntime', 60);
        
        $response->assertViewHas('slaPercentage'); 
    }

    public function test_pdf_export_endpoint_accessible()
    {
        $user = User::factory()->create();
        // Create a summary for last month to allow export
        MonthlySummary::create([
            'month' => now()->subMonth()->format('Y-m'),
            'total_incident' => 5,
            'total_downtime' => 100,
            'sla_percentage' => 99.5,
            'total_activation' => 2,
            'total_upgrade' => 1
        ]);

        $response = $this->actingAs($user)->get(route('dashboard.export'));
        $response->assertStatus(200); // dompdf stream returns 200
    }

    public function test_generate_monthly_summary_command()
    {
        $monthStr = now()->subMonth()->format('m');
        $yearStr = now()->subMonth()->format('Y');

        $customer = Customer::create([
             'name' => 'Test Customer',
             'service_type' => 'metro',
             'bandwidth' => '10 Mbps', 
             'status' => 'active'
        ]);

        // Incident last month
        CustomerIncident::create([
            'customer_id' => $customer->id,
            'incident_date' => now()->subMonth(),
            'duration' => 120,
            'status' => 'closed',
            'notes' => 'Past incident',
            'physical_issue' => false,
            'backbone_issue' => false,
            'layer_issue' => 'Layer 3',
            'root_cause' => 'Fiber cut'
        ]);

        Artisan::call('report:generate-monthly');

        $this->assertDatabaseHas('monthly_summaries', [
            'month' => now()->subMonth()->format('Y-m'),
            'total_incident' => 1,
            'total_downtime' => 120
        ]);
    }
}
