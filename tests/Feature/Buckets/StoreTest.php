<?php

namespace Tests\Feature\Buckets;

use Hydrofon\Bucket;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buckets can be created and stored.
     *
     * @return void
     */
    public function testBucketsCanBeStored()
    {
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->make();

        $response = $this->actingAs($admin)->post('buckets', [
            'name' => $bucket->name,
        ]);

        $response->assertRedirect('/buckets');
        $this->assertDatabaseHas('buckets', [
            'name' => $bucket->name,
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

        $response = $this->actingAs($admin)->post('buckets', [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Bucket::all());
    }

    /**
     * Non-admin users can not store buckets.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreBuckets()
    {
        $user = factory(User::class)->create();
        $bucket = factory(Bucket::class)->make();

        $response = $this->actingAs($user)->post('buckets', [
            'name' => $bucket->name,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('buckets', [
            'name' => $bucket->name,
        ]);
    }
}
