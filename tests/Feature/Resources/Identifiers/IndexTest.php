<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
