<?php

namespace Tests\Feature\Authentication;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User can log out.
     *
     * @return void
     */
    public function testUserCanLogOut()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect();
        $this->assertGuest();
    }
}
