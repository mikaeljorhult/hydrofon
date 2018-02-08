<?php

namespace Tests\Feature\Calendar;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user can visit calendar.
     *
     * @return void
     */
    public function testUserCanVisitCalendar()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertDontSee('"segel-object"');
    }

    /**
     * Requested resources are stored in session.
     *
     * @return void
     */
    public function testResourcesAreAddedToSession()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($user)->post('/calendar', [
            'resources' => [$resource->id],
        ]);

        $response->assertRedirect('/calendar');
        $response->assertSessionHas('resources', [$resource->id]);
    }

    /**
     * Requested resources are not stored in session if not in database.
     *
     * @return void
     */
    public function testMissingResourcesAreNotAddedToSession()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/calendar', [
            'resources' => 100,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionMissing('resources');
    }

    /**
     * Requested date is used.
     *
     * @return void
     */
    public function testDateIsUsed()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/calendar', [
            'date' => '2017-01-01',
        ]);

        $response->assertRedirect('/calendar/2017-01-01');
    }

    /**
     * Requested resources are shown.
     *
     * @return void
     */
    public function testResourcesAreShown()
    {
        $user = factory(User::class)->create();
        $resources = factory(Resource::class)->create();

        $this->actingAs($user)->post('/calendar', [
            'resources' => [$resources->id],
        ]);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertViewHas('resources');
        $response->assertSee('segel-objects');
    }
}
