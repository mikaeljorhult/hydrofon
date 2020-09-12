<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Profile page is available.
     *
     * @return void
     */
    public function testProfileIsAvailable()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get('profile')
             ->assertSuccessful()
             ->assertSee($user->name);
    }
}
