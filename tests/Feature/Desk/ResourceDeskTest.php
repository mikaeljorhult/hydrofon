<?php

namespace Tests\Feature\Desk;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceDeskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources can be found by an identifier.
     *
     * @return void
     */
    public function testResourcesCanBeFoundByIdentifier(): void
    {
        $resource = Resource::factory()->create();
        $resource->identifiers()->create(['value' => 'resource-identifier']);

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/resource-identifier');

        $response->assertStatus(200);
        $response->assertSee($resource->name);
    }
}
