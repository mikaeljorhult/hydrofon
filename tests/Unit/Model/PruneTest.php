<?php

namespace Tests\Unit\Model;

use App\Models\Booking;
use App\Models\User;
use App\States\CheckedOut;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PruneTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings less than 6 months old are not deleted.
     *
     * @return void
     */
    public function testBookingsOlderThanSixMonthsAreDeleted()
    {
        Booking::factory()->times(2)->create([
            'start_time' => now()->subMonths(7),
            'end_time'   => now()->subMonths(6),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings less than 6 months old are not deleted.
     *
     * @return void
     */
    public function testCheckedOutBookingsAreNotDeleted()
    {
        $booking = Booking::withoutEvents(function () {
            return Booking::factory()->create([
                'start_time' => now()->subMonths(7),
                'end_time'   => now()->subMonths(6),
                'state'      => CheckedOut::class,
            ]);
        });

        $this->artisan('model:prune');

        $this->assertModelExists($booking);
    }

    /**
     * Bookings that ended less than 6 months ago are not deleted.
     *
     * @return void
     */
    public function testNewlyEndedBookingsAreNotDeleted()
    {
        Booking::factory()->create([
            'start_time' => now()->subMonths(2),
            'end_time'   => now()->subMonths(1),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Current bookings are not deleted.
     *
     * @return void
     */
    public function testCurrentBookingsAreNotDeleted()
    {
        Booking::factory()->create([
            'start_time' => now()->subMonth(),
            'end_time'   => now()->addMonth(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Bookings in the future are not deleted.
     *
     * @return void
     */
    public function testFutureBookingsAreNotDeleted()
    {
        Booking::factory()->create([
            'start_time' => now()->addMonths(1),
            'end_time'   => now()->addMonths(2),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Users that haven't logged in for over a year are deleted.
     *
     * @return void
     */
    public function testNoneActiveUsersAreDeleted()
    {
        User::factory()->create([
            'last_logged_in_at' => now()->subYear(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(0, User::all());
    }

    /**
     * Users created over a year ago that haven't logged in are deleted.
     *
     * @return void
     */
    public function testOldAndInactiveUsersAreDeleted()
    {
        User::factory()->create([
            'created_at' => now()->subYear(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(0, User::all());
    }

    /**
     * Users that haven't logged in are not deleted.
     *
     * @return void
     */
    public function testNewlyCreatedUsersThatHaveNotLoggedInAreNotDeleted()
    {
        User::factory()->create();

        $this->artisan('model:prune');

        $this->assertCount(1, User::all());
    }

    /**
     * Users that have logged in within the last year are not deleted.
     *
     * @return void
     */
    public function testActiveUsersAreNotDeleted()
    {
        User::factory()->create([
            'last_logged_in_at' => now()->subMonth(),
        ]);

        User::factory()->create([
            'created_at'        => now()->subYears(2),
            'last_logged_in_at' => now()->subMonth(),
        ]);

        $this->artisan('model:prune');

        $this->assertCount(2, User::all());
    }

    /**
     * Users that have checked out bookings are not deleted.
     *
     * @return void
     */
    public function testUsersWithCheckedOutBookingsAreNotDeleted()
    {
        $user = User::factory()->create([
            'created_at' => now()->subYear(),
        ]);

        Booking::withoutEvents(function () use ($user) {
            return Booking::factory()->for($user)->checkedout()->create();
        });

        $this->artisan('model:prune');

        $this->assertModelExists($user);
    }
}
