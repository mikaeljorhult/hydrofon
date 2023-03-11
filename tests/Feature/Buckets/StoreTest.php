<?php

namespace Tests\Feature\Buckets;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a bucket.
     */
    public function storeBucket(array $overrides = [], ?User $user = null): TestResponse
    {
        $bucket = Bucket::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->post('buckets', $bucket->toArray());
    }

    /**
     * Buckets can be created and stored.
     */
    public function testBucketsCanBeStored(): void
    {
        $this->storeBucket(['name' => 'New Bucket'])
             ->assertRedirect('/buckets');

        $this->assertDatabaseHas('buckets', [
            'name' => 'New Bucket',
        ]);
    }

    /**
     * Buckets must have a name.
     */
    public function testBucketsMustHaveAName(): void
    {
        $this->storeBucket(['name' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('name');

        $this->assertCount(0, Bucket::all());
    }

    /**
     * Non-admin users can not store buckets.
     */
    public function testNonAdminUsersCanNotStoreBuckets(): void
    {
        $user = User::factory()->create();

        $this->storeBucket([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Bucket::all());
    }
}
