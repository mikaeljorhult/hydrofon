<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function testLandingPageLinkToRegisterRoute(): void
    {
        $this
            ->get('/')
            ->assertStatus(200)
            ->assertSee(route('register'));
    }

    public function testRegisterRouteIsSuccessful(): void
    {
        $this
            ->get('/register')
            ->assertStatus(200);
    }

    public function testGuestCanRegisterAccount(): void
    {
        $this
            ->post('/register', [
                'name' => 'New User',
                'email' => 'test@hydrofon.se',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirect('/home');

        $this->assertAuthenticated();
        $this->assertDatabaseHas(User::class, [
            'name' => 'New User',
            'email' => 'test@hydrofon.se',
        ]);
    }

    public function testEmailMustBePresent(): void
    {
        $this
            ->from('/register')
            ->post('/register', [
                'name' => 'New User',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertSessionHasErrors(['email'])
            ->assertRedirect('/register');

        $this->assertGuest();
        $this->assertDatabaseMissing(User::class, [
            'name' => 'New User',
        ]);
    }

    public function testEmailMustBeUnique(): void
    {
        User::factory()->create([
            'email' => 'test@hydrofon.se',
        ]);

        $this
            ->from('/register')
            ->post('/register', [
                'name' => 'New User',
                'email' => 'test@hydrofon.se',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertSessionHasErrors(['email'])
            ->assertRedirect('/register');

        $this->assertGuest();
    }
}
