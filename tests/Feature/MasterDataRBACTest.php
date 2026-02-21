<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class MasterDataRBACTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_view_locations_index()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('locations.index'));
        $response->assertStatus(200);
    }

    public function test_manager_cannot_create_location()
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $response = $this->actingAs($manager)->get(route('locations.create'));
        $response->assertStatus(403); // Forbidden
    }

    public function test_noc_can_view_create_page()
    {
        $noc = User::factory()->create(['role' => 'noc']);
        $response = $this->actingAs($noc)->get(route('locations.create'));
        $response->assertStatus(200);
    }

    public function test_admin_can_view_create_page()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get(route('locations.create'));
        $response->assertStatus(200);
    }
}
