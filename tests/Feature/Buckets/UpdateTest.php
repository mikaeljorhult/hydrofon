<?php

namespace Tests\Feature\Buckets;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Edit route is available.
     *
     * @return void
     */
    public function testEditRouteIsAvailable()
    {
        $bucket = Bucket::factory()->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('buckets.edit', $bucket))
            ->assertSuccessful()
            ->assertSee($bucket->name);
    }

    /**
     * Buckets can be updated.
     *
     * @return void
     */
    public function testBucketsCanBeUpdated()
    {
        $admin = User::factory()->admin()->create();
        $bucket = Bucket::factory()->create();

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
        $admin = User::factory()->admin()->create();
        $bucket = Bucket::factory()->create();

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
        $user = User::factory()->create();
        $bucket = Bucket::factory()->create();

        $response = $this->actingAs($user)->put('buckets/'.$bucket->id, [
            'name' => 'New Bucket Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('buckets', [
            'id' => $bucket->id,
            'name' => $bucket->name,
        ]);
    }
}
