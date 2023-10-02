<?php

namespace Tests\Browser\Bookings;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BookingsIndexTest extends DuskTestCase
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

    public function testMultipleItemsCanBeDeleted(): void
    {
        $bookings = Booking::factory(5)->create();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->check('[name="selected[]"][value="'.$bookings->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$bookings->last()->id.'"]')
                ->click('@delete-multiple')
                ->waitUntilMissing('@item-'.$bookings->first()->id)
                ->logout();
        });

        $this->assertDatabaseCount(Booking::class, 3);
        $this->assertModelMissing($bookings->first());
        $this->assertModelMissing($bookings->last());
    }

    public function testMultipleItemsCanBeCheckedOut(): void
    {
        $bookings = Booking::factory(5)->create();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->check('[name="selected[]"][value="'.$bookings->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$bookings->last()->id.'"]')
                ->click('@checkout-multiple')
                ->waitForTextIn('@item-'.$bookings->first()->id, 'Checked out')
                ->logout();
        });

        $bookings->each->refresh();

        $this->assertTrue($bookings->first()->isCheckedOut);
        $this->assertTrue($bookings->last()->isCheckedOut);
        $this->assertFalse($bookings[1]->isCheckedOut);
        $this->assertFalse($bookings[2]->isCheckedOut);
        $this->assertFalse($bookings[3]->isCheckedOut);
    }

    public function testMultipleItemsCanBeCheckedIn(): void
    {
        $bookings = Booking::factory(5)->checkedout()->createQuietly();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->check('[name="selected[]"][value="'.$bookings->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$bookings->last()->id.'"]')
                ->click('@checkin-multiple')
                ->waitForTextIn('@item-'.$bookings->first()->id, 'Checked in')
                ->logout();
        });

        $bookings->each->refresh();

        $this->assertTrue($bookings->first()->isCheckedIn);
        $this->assertTrue($bookings->last()->isCheckedIn);
        $this->assertFalse($bookings[1]->isCheckedIn);
        $this->assertFalse($bookings[2]->isCheckedIn);
        $this->assertFalse($bookings[3]->isCheckedIn);
    }

    public function testMultipleItemsCanBeApproved(): void
    {
        $this->setConfig('hydrofon.require_approval', 'all');

        $bookings = Booking::factory(5)->pending()->createQuietly();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->check('[name="selected[]"][value="'.$bookings->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$bookings->last()->id.'"]')
                ->click('@approve-multiple')
                ->waitForTextIn('@item-'.$bookings->first()->id, 'Approved')
                ->logout();
        });

        $bookings->each->refresh();

        $this->assertTrue($bookings->first()->isApproved);
        $this->assertTrue($bookings->last()->isApproved);
        $this->assertFalse($bookings[1]->isApproved);
        $this->assertFalse($bookings[2]->isApproved);
        $this->assertFalse($bookings[3]->isApproved);

        $this->resetConfig();
    }

    public function testMultipleItemsCanBeRejected(): void
    {
        $this->setConfig('hydrofon.require_approval', 'all');

        $bookings = Booking::factory(5)->pending()->createQuietly();

        $this->browse(function (Browser $browser) use ($bookings) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->check('[name="selected[]"][value="'.$bookings->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$bookings->last()->id.'"]')
                ->click('@reject-multiple')
                ->waitForTextIn('@item-'.$bookings->first()->id, 'Rejected')
                ->logout();
        });

        $bookings->each->refresh();

        $this->assertTrue($bookings->first()->isRejected);
        $this->assertTrue($bookings->last()->isRejected);
        $this->assertFalse($bookings[1]->isRejected);
        $this->assertFalse($bookings[2]->isRejected);
        $this->assertFalse($bookings[3]->isRejected);

        $this->resetConfig();
    }
}
