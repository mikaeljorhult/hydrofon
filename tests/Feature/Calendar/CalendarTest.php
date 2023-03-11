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
     */
    public function testUserCanVisitCalendar(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertDontSee('"segel-resource"');
    }

    /**
     * Requested resources are stored in session.
     */
    public function testResourcesAreAddedToSession(): void
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
     */
    public function testMissingResourcesAreNotAddedToSession(): void
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
     */
    public function testDateIsUsed(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/calendar', [
            'date' => '2017-01-01',
        ]);

        $response->assertRedirect('/calendar/2017-01-01');
    }

    /**
     * Requested resources are shown.
     */
    public function testResourcesAreShown(): void
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
