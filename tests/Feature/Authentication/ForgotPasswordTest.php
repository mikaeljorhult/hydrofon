<?php

namespace Tests\Feature\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_route_is_successful(): void
    {
        $this
            ->get('forgot-password')
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function test_user_can_request_password_reset(): void
    {
        $user = User::factory()->create();

        $this
            ->from('forgot-password')
            ->post('forgot-password', [
                'email' => $user->email,
            ])
            ->assertRedirect();

        $emails = app('mailer')->getSymfonyTransport()->messages();
        $recipient = $emails->first()->getEnvelope()->getRecipients()[0]->getAddress();

        $this->assertCount(1, $emails);
        $this->assertEquals($user->email, $recipient);
    }

    public function test_user_must_exist(): void
    {
        $this
            ->from('forgot-password')
            ->post('forgot-password', [
                'email' => 'test@hydrofon.se',
            ])
            ->assertSessionHasErrors(['email'])
            ->assertRedirect('forgot-password');
    }
}
