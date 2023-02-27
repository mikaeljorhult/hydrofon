<?php

namespace Tests\Feature\Bookings;

use App\Models\Resource;
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
    public function testCreateRouteIsServedSuccessfully()
    {
        Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('bookings/create')
             ->assertSuccessful();
    }
}
