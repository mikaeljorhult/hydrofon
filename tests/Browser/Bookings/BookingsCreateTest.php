<?php

namespace Tests\Browser\Bookings;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BookingsCreateTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function testEditRouteIsReachable(): void
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

    public function testItemCanBeEdited(): void
    {
        $resources = Resource::factory(5)->create();
        $users = User::factory(5)->create();

        $this->browse(function (Browser $browser) use ($resources, $users) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings/create')
                ->assertSee('Create booking')
                ->select('resource_id', $resources->last()->id)
                ->select('user_id', $users->last()->id);

            $browser
                ->script([
                    'document.querySelector("#start_time")._flatpickr.setDate("'.now()->addHours()->format('Y-m-d H:i').'")',
                    'document.querySelector("#end_time")._flatpickr.setDate("'.now()->addHours(2)->format('Y-m-d H:i').'")',
                ]);

            $browser
                ->clickAndWaitForReload('@submitcreate')
                ->assertPathIs('/bookings')
                ->logout();
        });

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $resources->last()->id,
            'user_id' => $users->last()->id,
        ]);
    }

    public function testEditCanBeCancelled(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/bookings/create')
                ->click('@submitcancel')
                ->assertPathIs('/bookings')
                ->logout();
        });
    }
}
