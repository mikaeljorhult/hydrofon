<?php

namespace Tests\Unit\Commands;

use App\Booking;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CleanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings less than 6 months old are not deleted.
     *
     * @return void
     */
    public function testBookingsOlderThanSixMonthsAreDeleted()
    {
        factory(Booking::class, 2)->create([
            'start_time' => now()->subMonths(7),
            'end_time'   => now()->subMonths(6),
        ]);

        $this->artisan('hydrofon:clean');

        $this->assertCount(0, Booking::all());
    }

    /**
     * Bookings that ended less than 6 months ago are not deleted.
     *
     * @return void
     */
    public function testNewlyEndedBookingsAreNotDeleted()
    {
        factory(Booking::class)->create([
            'start_time' => now()->subMonths(2),
            'end_time'   => now()->subMonths(1),
        ]);

        $this->artisan('hydrofon:clean');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Current bookings are not deleted.
     *
     * @return void
     */
    public function testCurrentBookingsAreNotDeleted()
    {
        factory(Booking::class)->create([
            'start_time' => now()->subMonth(),
            'end_time'   => now()->addMonth(),
        ]);

        $this->artisan('hydrofon:clean');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Bookings in the future are not deleted.
     *
     * @return void
     */
    public function testFutureBookingsAreNotDeleted()
    {
        factory(Booking::class)->create([
            'start_time' => now()->addMonths(1),
            'end_time'   => now()->addMonths(2),
        ]);

        $this->artisan('hydrofon:clean');

        $this->assertCount(1, Booking::all());
    }

    /**
     * Users that haven't logged in for over a year are deleted.
     *
     * @return void
     */
    public function testNoneActiveUsersAreDeleted()
    {
        factory(User::class)->create([
            'last_logged_in_at' => now()->subYear(),
        ]);

        $this->artisan('hydrofon:clean');

        $this->assertCount(0, User::all());
    }

    /**
     * Users created over a year ago that haven't logged in are deleted.
     *
     * @return void
     */
    public function testOldAndInactiveUsersAreDeleted()
    {
        factory(User::class)->create([
            'created_at' => now()->subYear(),
        ]);

        $this->artisan('hydrofon:clean');

        $this->assertCount(0, User::all());
    }

    /**
     * Users that haven't logged in are not deleted.
     *
     * @return void
     */
    public function testNewlyCreatedUsersThatHaveNotLoggedInAreNotDeleted()
    {
        factory(User::class)->create();

        $this->artisan('hydrofon:clean');

        $this->assertCount(1, User::all());
    }

    /**
     * Users that have logged in within the last year are not deleted.
     *
     * @return void
     */
    public function testActiveUsersAreNotDeleted()
    {
        factory(User::class)->create([
            'last_logged_in_at' => now()->subMonth(),
        ]);

        factory(User::class)->create([
            'created_at'        => now()->subYears(2),
            'last_logged_in_at' => now()->subMonth(),
        ]);

        $this->artisan('hydrofon:clean');

        $this->assertCount(2, User::all());
    }
}
