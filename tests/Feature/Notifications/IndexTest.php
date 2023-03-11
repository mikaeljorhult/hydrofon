<?php

namespace Tests\Feature\Notifications;

use App\Models\User;
use App\Notifications\BookingOverdue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A visitor can not visit notifications.
     */
    public function testVisitorAreRedirectedToLogin(): void
    {
        $this->get('notifications')
             ->assertRedirect('login');
    }

    /**
     * A user can visit notifications.
     */
    public function testUserCanSeeNotifications(): void
    {
        $this
            ->actingAs(User::factory()->create())
            ->get('notifications')
            ->assertOk()
            ->assertSeeText('You have no new notifications.');
    }

    /**
     * Booking overdue notification is displayed.
     */
    public function testOverdueNotificationIsDisplayed(): void
    {
        $user = User::factory()->create();
        $user->notify(new BookingOverdue());

        $this
            ->actingAs($user)
            ->get('notifications')
            ->assertOk()
            ->assertSeeText('overdue');
    }
}
