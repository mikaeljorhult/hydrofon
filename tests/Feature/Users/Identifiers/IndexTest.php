<?php

namespace Tests\Feature\Users\Identifiers;

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
        $user = User::factory()->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $this->actingAs(User::factory()->admin()->create())
             ->get('users/'.$user->id.'/identifiers')
             ->assertSuccessful()
             ->assertSee($identifier->value);
    }
}
