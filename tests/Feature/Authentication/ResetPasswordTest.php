<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_route_is_successful(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this
            ->get('reset-password/'.$token)
            ->assertOk()
            ->assertViewIs('auth.reset-password');
    }

    public function test_password_can_be_reset(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this
            ->post('reset-password', [
                'token' => $token,
                'email' => $user->email,
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirectToRoute('login');

        $user->refresh();
        $this->assertTrue(Hash::check('new-password', $user->password));
    }
}
