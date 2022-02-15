<?php

namespace Tests\Feature\Calendar;

use App\Models\Resource;
use App\Models\User;
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
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertDontSee('"segel-resource"');
    }

    /**
     * Requested resources are stored in session.
     *
     * @return void
     */
    public function testResourcesAreAddedToSession()
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

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
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/calendar', [
            'resources' => [100],
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
        $user = User::factory()->create();

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
        $user = User::factory()->create();
        $resources = Resource::factory()->create();

        $this->actingAs($user)->post('/calendar', [
            'resources' => [$resources->id],
        ]);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertViewHas('resources');
        $response->assertSee('segel-resources');
    }
}
