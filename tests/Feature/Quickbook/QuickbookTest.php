<?php

namespace Tests\Feature\Quickbook;

use App\Http\Livewire\QuickBook;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire;
use Tests\TestCase;

class QuickbookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Component is rendered correctly.
     *
     * @return void
     */
    public function testComponentIsRendered()
    {
        Resource::factory()->create();

        Livewire::test(QuickBook::class)
                ->assertSee('Quick Book')
                ->assertOk();
    }

    /**
     * Available resources are prefetched.
     *
     * @return void
     */
    public function testAvailableResourcesArePrefetched()
    {
        $resource = Resource::factory()->create();

        Livewire::test(QuickBook::class)
                ->call('loadResources')
                ->assertSee($resource->name)
                ->assertOk();
    }

    /**
     * Unavailable resources are omitted.
     *
     * @return void
     */
    public function testUnavailableResourcesAreOmitted()
    {
        $booking = Booking::factory()->current()->create();

        Livewire::test(QuickBook::class)
                ->assertDontSee($booking->resource->name)
                ->assertOk();
    }

    /**
     * Bookings can be created.
     *
     * @return void
     */
    public function testBookingIsCreated()
    {
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->create())
                ->test(QuickBook::class)
                ->call('loadResources')
                ->set('resource_id', $resource->id)
                ->call('book')
                ->assertDispatchedBrowserEvent('booking-created')
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Start time, end time and resource must be present.
     *
     * @return void
     */
    public function testAllParametersMustBePresent()
    {
        Livewire::actingAs(User::factory()->create())
                ->test(QuickBook::class)
                ->call('loadResources')
                ->set('start_time', '')
                ->set('end_time', '')
                ->set('resource_id', '')
                ->call('book')
                ->assertHasErrors(['start_time', 'end_time', 'resource_id'])
                ->assertNotDispatchedBrowserEvent('booking-created')
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseCount(Booking::class, 0);
    }

    /**
     * End time must be after start time.
     *
     * @return void
     */
    public function testEndTimeMustBeAfterStartTime()
    {
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->create())
                ->test(QuickBook::class)
                ->call('loadResources')
                ->set('start_time', now())
                ->set('end_time', now()->subHour())
                ->set('resource_id', $resource->id)
                ->call('book')
                ->assertHasErrors(['end_time'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseCount(Booking::class, 0);
    }

    /**
     * Resource must exist.
     *
     * @return void
     */
    public function testResourceMustExist()
    {
        Livewire::actingAs(User::factory()->create())
                ->test(QuickBook::class)
                ->set('start_time', now())
                ->set('end_time', now()->addHour())
                ->set('resource_id', 100)
                ->call('book')
                ->assertHasErrors(['resource_id'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseCount(Booking::class, 0);
    }

    /**
     * Resource must be available.
     *
     * @return void
     */
    public function testResourceMustBeAvailable()
    {
        $booking = Booking::factory()->current()->createQuietly();

        Livewire::actingAs(User::factory()->create())
                ->test(QuickBook::class)
                ->set('start_time', now())
                ->set('end_time', now()->addHour())
                ->set('resource_id', $booking->resource_id)
                ->call('book')
                ->assertHasErrors(['resource_id'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseCount(Booking::class, 1);
    }

    /**
     * Error notifications are reset on next request.
     *
     * @return void
     */
    public function testErrorNotificationsAreReset()
    {
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->create())
                ->test(QuickBook::class)
                ->set('start_time', now())
                ->set('end_time', now()->addHour())

                // Error on missing resource.
                ->set('resource_id', 100)
                ->call('book')
                ->assertHasErrors(['resource_id'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                })

                // Successful on existing resource.
                ->set('resource_id', $resource->id)
                ->call('book')
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertDatabaseCount(Booking::class, 1);
    }
}
