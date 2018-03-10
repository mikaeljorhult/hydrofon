<?php

namespace Tests\Feature\Buckets;

use Hydrofon\Bucket;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buckets can be deleted.
     *
     * @return void
     */
    public function testBucketsCanBeDeleted()
    {
        $admin  = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($admin)->delete('buckets/'.$bucket->id);

        $response->assertRedirect('/buckets');
        $this->assertDatabaseMissing('buckets', [
            'name' => $bucket->name,
        ]);
    }

    /**
     * Non-admin users can not delete buckets.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteBuckets()
    {
        $user   = factory(User::class)->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($user)->delete('buckets/'.$bucket->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('buckets', [
            'id'   => $bucket->id,
            'name' => $bucket->name,
        ]);
    }
}
