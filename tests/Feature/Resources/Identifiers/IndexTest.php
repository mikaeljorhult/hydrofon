<?php

namespace Tests\Feature\Resources\Identifiers;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources are listed in index.
     *
     * @return void
     */
    public function testResourcesAreListed()
    {
        $resource = factory(Resource::class)->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('resources/'.$resource->id.'/identifiers')
             ->assertSuccessful()
             ->assertSee(e($identifier->value));
    }
}
