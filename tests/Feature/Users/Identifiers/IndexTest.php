<?php

namespace Tests\Feature\Users\Identifiers;

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
        $user = factory(User::class)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('users/'.$user->id.'/identifiers')
             ->assertSuccessful()
             ->assertSee($identifier->value);
    }
}
