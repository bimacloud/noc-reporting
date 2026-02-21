<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_user_role_helpers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $noc = User::factory()->create(['role' => 'noc']);
        $manager = User::factory()->create(['role' => 'manager']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isNoc());
        $this->assertFalse($admin->isManager());

        $this->assertFalse($noc->isAdmin());
        $this->assertTrue($noc->isNoc());
        $this->assertFalse($noc->isManager());

        $this->assertFalse($manager->isAdmin());
        $this->assertFalse($manager->isNoc());
        $this->assertTrue($manager->isManager());
    }

    public function test_role_middleware_blocks_unauthorized_users(): void
    {
        $user = User::factory()->create(['role' => 'noc']);

        // Create a temporary route for testing
        Route::get('/admin-only', function () {
            return 'Admin Content';
        })->middleware('role:admin');

        $response = $this->actingAs($user)->get('/admin-only');

        $response->assertStatus(403);
    }

    public function test_role_middleware_allows_authorized_users(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        // Create a temporary route for testing
        Route::get('/admin-only-2', function () {
            return 'Admin Content';
        })->middleware('role:admin');

        $response = $this->actingAs($user)->get('/admin-only-2');

        $response->assertStatus(200);
        $response->assertSee('Admin Content');
    }
}
