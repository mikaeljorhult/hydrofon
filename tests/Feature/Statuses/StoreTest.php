<?php

namespace Tests\Feature\Statuses;

use App\Models\Resource;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Status can be set on resource.
     */
    public function testStatusCanBeSet(): void
    {
        $resource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->post('resources/'.$resource->id.'/statuses', [
                 'name' => 'broken',
                 'reason' => 'Comment',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas(Status::class, [
            'name' => 'broken',
            'reason' => 'Comment',
        ]);
    }

    /**
     * Regular users can not set status.
     */
    public function testUserCanNotSetStatus(): void
    {
        $resource = Resource::factory()->create();

        $this->actingAs(User::factory()->create())
             ->post('resources/'.$resource->id.'/statuses', [
                 'name' => 'broken',
                 'reason' => 'Comment',
             ])
             ->assertForbidden();

        $this->assertDatabaseCount(Status::class, 0);
    }

    /**
     * Statuses are validated.
     */
    public function testInvalidStatusIsCaughtByValidation(): void
    {
        $resource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->post('resources/'.$resource->id.'/statuses', [
                 'name' => 'invalid-status',
             ])
             ->assertSessionHasErrors('name');

        $this->assertDatabaseCount(Status::class, 0);
    }

    /**
     * Any status changes are logged.
     */
    public function testStatusChangesAreLogged(): void
    {
        $resource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->post('resources/'.$resource->id.'/statuses', [
                 'name' => 'broken',
             ])
             ->assertRedirect();

        $this->assertDatabaseHas(Activity::class, [
            'event' => 'broken',
            'description' => 'flagged',
            'subject_type' => Resource::class,
            'subject_id' => $resource->id,
        ]);
    }
}
