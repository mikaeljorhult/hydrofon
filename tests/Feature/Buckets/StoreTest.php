<?php

namespace Tests\Feature\Buckets;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a bucket.
     *
     * @param array               $overrides
     * @param \App\Models\User|null $user
     *
     * @return \Illuminate\Testing\TestResponse
     */
    public function storeBucket($overrides = [], $user = null)
    {
        $bucket = Bucket::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->post('buckets', $bucket->toArray());
    }

    /**
     * Buckets can be created and stored.
     *
     * @return void
     */
    public function testBucketsCanBeStored()
    {
        $this->storeBucket(['name' => 'New Bucket'])
             ->assertRedirect('/buckets');

        $this->assertDatabaseHas('buckets', [
            'name' => 'New Bucket',
        ]);
    }

    /**
     * Buckets must have a name.
     *
     * @return void
     */
    public function testBucketsMustHaveAName()
    {
        $this->storeBucket(['name' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('name');

        $this->assertCount(0, Bucket::all());
    }

    /**
     * Non-admin users can not store buckets.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreBuckets()
    {
        $user = User::factory()->create();

        $this->storeBucket([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Bucket::all());
    }
}
