<?php

namespace Tests\Unit\Model;

use App\Models\Booking;
use App\Models\User;
use App\Settings\General;
use App\States\CheckedOut;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruneTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings less than 6 months old are not deleted.
     */
    public function testBookingsOlderThanSixMonthsAreDeleted(): void
    {
        Booking::factory()->times(2)->create([
            'start_time' => now()->subMonths(7),
            'end_time' => now()->subMonths(6),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings less than 6 months old are not deleted.
     */
    public function testCheckedOutBookingsAreNotDeleted(): void
    {
        $booking = Booking::factory()->createQuietly([
            'start_time' => now()->subMonths(7),
            'end_time' => now()->subMonths(6),
            'state' => CheckedOut::class,
        ]);

        $this->artisan('model:prune');

        $this->assertModelExists($booking);
    }

    /**
     * Bookings that ended less than 6 months ago are not deleted.
     */
    public function testNewlyEndedBookingsAreNotDeleted(): void
    {
        Booking::factory()->create([
            'start_time' => now()->subMonths(2),
            'end_time' => now()->subMonths(1),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Current bookings are not deleted.
     */
    public function testCurrentBookingsAreNotDeleted(): void
    {
        Booking::factory()->create([
            'start_time' => now()->subMonth(),
            'end_time' => now()->addMonth(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Bookings in the future are not deleted.
     */
    public function testFutureBookingsAreNotDeleted(): void
    {
        Booking::factory()->create([
            'start_time' => now()->addMonths(1),
            'end_time' => now()->addMonths(2),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Users that haven't logged in for over a year are deleted.
     */
    public function testNoneActiveUsersAreDeleted(): void
    {
        User::factory()->create([
            'last_logged_in_at' => now()->subYear(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(0, User::all());
    }

    /**
     * Users created over a year ago that haven't logged in are deleted.
     */
    public function testOldAndInactiveUsersAreDeleted(): void
    {
        User::factory()->create([
            'created_at' => now()->subYear(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(0, User::all());
    }

    /**
     * Users that haven't logged in are not deleted.
     */
    public function testNewlyCreatedUsersThatHaveNotLoggedInAreNotDeleted(): void
    {
        User::factory()->create();

        $this->artisan('model:prune');

        $this->assertCount(1, User::all());
    }

    /**
     * Users that have logged in within the last year are not deleted.
     */
    public function testActiveUsersAreNotDeleted(): void
    {
        User::factory()->create([
            'last_logged_in_at' => now()->subMonth(),
        ]);

        User::factory()->create([
            'created_at' => now()->subYears(2),
            'last_logged_in_at' => now()->subMonth(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(2, User::all());
    }

    /**
     * Users that have checked out bookings are not deleted.
     */
    public function testUsersWithCheckedOutBookingsAreNotDeleted(): void
    {
        $user = User::factory()->create([
            'created_at' => now()->subYear(),
        ]);

        Booking::factory()->for($user)->checkedout()->createQuietly();

        $this->artisan('model:prune');

        $this->assertModelExists($user);
    }

    /**
     * Pruned users relationships are also deleted.
     */
    public function testRelationshipsAreDeletedWithTheirUser(): void
    {
        $user = User::factory()->create([
            'created_at' => now()->subYear(),
        ]);

        $booking = Booking::factory()->for($user)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);
        $subscription = $user->subscription()->create();

        $this->artisan('model:prune');

        $this->assertModelMissing($user);
        $this->assertModelMissing($booking);
        $this->assertModelMissing($identifier);
        $this->assertModelMissing($subscription);
    }

    /**
     * The time before users get pruned from the database can be
     * set in configuration.
     */
    public function testTimeBeforePruningUsersCanBeConfigured(): void
    {
        General::fake([
            'prune_users' => 1,
        ]);

        $user = User::factory()->create([
            'created_at' => now()->subDay(),
        ]);

        $this->artisan('model:prune');

        $this->assertModelMissing($user);
    }

    /**
     * The time before bookings get pruned from the database can be
     * set in configuration.
     */
    public function testTimeBeforePruningBookingsCanBeConfigured(): void
    {
        General::fake([
            'prune_bookings' => 1,
        ]);

        $booking = Booking::factory()->create([
            'end_time' => now()->subDay(),
        ]);

        $this->artisan('model:prune');

        $this->assertModelMissing($booking);
    }
}
