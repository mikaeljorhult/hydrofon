<?php

namespace Tests\Browser\Bookings;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BookingsEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testBookingsCreateIsReachable(): void
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings')
                ->assertSeeLink($booking->resource->name)
                ->clickLink($booking->resource->name)
                ->assertPathIs('/bookings/'.$booking->id.'/edit')
                ->assertSee('Edit booking')
                ->logout();
        });
    }

    public function testItemCanBeCreated(): void
    {
        $booking = Booking::factory()->create();
        $resources = Resource::factory(5)->create();
        $users = User::factory(5)->create();

        $this->browse(function (Browser $browser) use ($booking, $resources, $users) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings/'.$booking->id.'/edit')
                ->assertSee('Edit booking')
                ->select('resource_id', $resources->last()->id)
                ->select('user_id', $users->last()->id)
                ->clickAndWaitForReload('@submitupdate')
                ->assertPathIs('/bookings')
                ->assertSee($booking->refresh()->resource->name)
                ->assertSee($booking->user->name)
                ->logout();
        });

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $resources->last()->id,
            'user_id' => $users->last()->id,
        ]);
    }

    public function testCreateCanBeCancelled(): void
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings/'.$booking->id.'/edit')
                ->click('@submitcancel')
                ->assertPathIs('/bookings')
                ->logout();
        });
    }
}
