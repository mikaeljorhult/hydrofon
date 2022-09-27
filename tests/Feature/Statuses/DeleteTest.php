<?php

namespace Tests\Feature\Statuses;

use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Status can be deleted.
     *
     * @return void
     */
    public function testStatusCanDeleted()
    {
        $status = Status::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->delete('resources/'.$status->model_id.'/statuses/'.$status->id)
             ->assertRedirect();

        $this->assertDatabaseCount(Status::class, 0);
    }

    /**
     * Regular users can not set status.
     *
     * @return void
     */
    public function testUserCanNotDeleteStatus()
    {
        $status = Status::factory()->create();

        $this->actingAs(User::factory()->create())
             ->delete('resources/'.$status->model_id.'/statuses/'.$status->id)
             ->assertForbidden();

        $this->assertDatabaseCount(Status::class, 1);
    }
}
