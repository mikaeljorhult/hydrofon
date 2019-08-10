<?php

namespace Tests\Feature\Authentication;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Login route is available.
     *
     * @return void
     */
    public function testLoginRouteIsAvailable()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * User can log in.
     *
     * @return void
     */
    public function testUserCanLogIn()
    {
        $user = factory(User::class)->create();

        $response = $this->post('/login', [
            'email'    => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->last_logged_in_at);
        $this->assertInstanceOf(Carbon::class, $user->fresh()->last_logged_in_at);
    }
}
