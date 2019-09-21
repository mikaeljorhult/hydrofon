<?php

namespace Tests\Feature\Buckets;

use Hydrofon\Bucket;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buckets can be updated.
     *
     * @return void
     */
    public function testBucketsCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($admin)->put('buckets/'.$bucket->id, [
            'name' => 'New Bucket Name',
        ]);

        $response->assertRedirect('/buckets');
        $this->assertDatabaseHas('buckets', [
            'name' => 'New Bucket Name',
        ]);
    }

    /**
     * Buckets must have a name.
     *
     * @return void
     */
    public function testBucketsMustHaveAName()
    {
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($admin)->put('buckets/'.$bucket->id, [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('buckets', [
            'name' => $bucket->name,
        ]);
    }

    /**
     * Non-admin users can not update buckets.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateBuckets()
    {
        $user = factory(User::class)->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($user)->put('buckets/'.$bucket->id, [
            'name' => 'New Bucket Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('buckets', [
            'id'   => $bucket->id,
            'name' => $bucket->name,
        ]);
    }
}
