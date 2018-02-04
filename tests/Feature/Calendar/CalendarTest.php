<?php

namespace Tests\Feature\Calendar;

use Hydrofon\Object;
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
     * Requested objects are stored in session.
     *
     * @return void
     */
    public function testObjectsAreAddedToSession()
    {
        $user = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $response = $this->actingAs($user)->post('/calendar', [
            'objects' => [$object->id],
        ]);

        $response->assertRedirect('/calendar');
        $response->assertSessionHas('objects', [$object->id]);
    }

    /**
     * Requested objects are not stored in session if not in database.
     *
     * @return void
     */
    public function testMissingObjectsAreNotAddedToSession()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/calendar', [
            'objects' => 100,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionMissing('objects');
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
     * Requested objects are shown.
     *
     * @return void
     */
    public function testObjectsAreShown()
    {
        $user = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $this->actingAs($user)->post('/calendar', [
            'objects' => [$object->id],
        ]);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertViewHas('objects');
        $response->assertSee('segel-object');
    }
}
