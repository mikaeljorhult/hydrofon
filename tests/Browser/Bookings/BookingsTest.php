<?php

namespace Browser\Bookings;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BookingsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testBookingsIndexIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/calendar')
                ->assertSeeLink('Bookings')
                ->clickLink('Bookings')
                ->assertPathIs('/bookings')
                ->assertSee('Bookings')
                ->logout();
        });
    }

    public function testResourceAndUserAreDisplayedInListing(): void
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->assertSee($booking->resource->name)
                ->assertSee($booking->user->name)
                ->logout();
        });
    }

    public function testItemCanBeEditedInline(): void
    {
        $booking = Booking::factory()->create();
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($booking, $resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->mouseover('@item-'.$booking->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$booking->id)
                ->select('resource_id', $resource->id)
                ->press('Save')
                ->waitFor('@item-'.$booking->id)
                ->assertSee($resource->name)
                ->logout();
        });

        $this->assertDatabaseHas(Booking::class, [
            'id' => $booking->id,
            'resource_id' => $resource->id,
        ]);
    }

    public function testInlineEditingCanBeCancelled(): void
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->mouseover('@item-'.$booking->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$booking->id)
                ->press('Cancel')
                ->waitFor('@item-'.$booking->id)
                ->assertSee($booking->resource->name)
                ->logout();
        });
    }

    public function testItemCanBeDeleted(): void
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->mouseover('@item-'.$booking->id)
                ->click('@delete')
                ->waitUntilMissing('@item-'.$booking->id)
                ->assertDontSee($booking->resource->name)
                ->logout();
        });
    }

    public function testBookingsCreateIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->assertSeeLink('New booking')
                ->clickLink('New booking')
                ->assertPathIs('/bookings/create')
                ->assertSee('Create booking')
                ->logout();
        });
    }

    public function testItemLinkToCalendarView(): void
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->mouseover('@item-'.$booking->id)
                ->clickAndWaitForReload('@viewincalendar')
                ->assertPathIs('/calendar/'.$booking->start_time->format('Y-m-d'))
                ->assertPresent('.segel-booking[data-id="'.$booking->id.'"]')
                ->logout();
        });
    }

    public function testItemCanBeCheckedOut(): void
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->mouseover('@item-'.$booking->id)
                ->click('@checkout')
                ->waitFor('@checkin')
                ->logout();
        });

        $booking->refresh();

        $this->assertTrue($booking->isCheckedOut);
    }

    public function testItemCanBeCheckedIn(): void
    {
        $booking = Booking::factory()->checkedout()->createQuietly();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->mouseover('@item-'.$booking->id)
                ->click('@checkin')
                ->waitUntilMissing('@checkin')
                ->logout();
        });

        $booking->refresh();

        $this->assertTrue($booking->isCheckedIn);
    }
}
