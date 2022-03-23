<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resource is displayed to administrators.
     *
     * @return void
     */
    public function testIdentifierIsDisplayed()
    {
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $this->actingAs(User::factory()->admin()->create())
             ->get('resources/'.$resource->id.'/identifiers/'.$identifier->id)
             ->assertSuccessful()
             ->assertSee($resource->name)
             ->assertViewHas('code');
    }

    /**
     * Non-admin users can not show identifier.
     *
     * @return void
     */
    public function testUsersCanNotShowIdentifier()
    {
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $this->actingAs(User::factory()->create())
             ->get('resources/'.$resource->id.'/identifiers/'.$identifier->id)
             ->assertForbidden();
    }

    /**
     * Visitors can not show identifier.
     *
     * @return void
     */
    public function testVisitorsCanNotShowIdentifier()
    {
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $this->get('resources/'.$resource->id.'/identifiers/'.$identifier->id)
             ->assertRedirect('login');
    }
}
