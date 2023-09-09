<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources are listed in index.
     */
    public function testResourcesAreListed(): void
    {
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $this->actingAs(User::factory()->admin()->create())
            ->get('resources/'.$resource->id.'/identifiers')
            ->assertSuccessful()
            ->assertSee($identifier->value);
    }
}
