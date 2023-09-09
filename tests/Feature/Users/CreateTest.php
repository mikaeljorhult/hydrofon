<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create route is served successfully.
     */
    public function testCreateRouteIsServedSuccessfully(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('users/create')
            ->assertSuccessful();
    }
}
