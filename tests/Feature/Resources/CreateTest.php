<?php

namespace Tests\Feature\Resources;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create route is served successfully.
     *
     * @return void
     */
    public function testCreateRouteIsServedSuccessfully(): void
    {
        $this->actingAs(User::factory()->admin()->create())
             ->get('resources/create')
             ->assertSuccessful();
    }
}
