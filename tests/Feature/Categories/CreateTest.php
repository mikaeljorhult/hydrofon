<?php

namespace Tests\Feature\Categories;

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
        $this->actingAs(User::factory()->admin()->create())
             ->get('categories/create')
             ->assertSuccessful();
    }
}
