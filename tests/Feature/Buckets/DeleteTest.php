<?php

namespace Tests\Feature\Buckets;

use App\Models\Bucket;
use App\Models\User;
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
        $admin = User::factory()->admin()->create();
        $bucket = Bucket::factory()->create();

        $response = $this->actingAs($admin)->delete('buckets/'.$bucket->id);

        $response->assertRedirect('/buckets');
        $this->assertModelMissing($bucket);
    }

    /**
     * Non-admin users can not delete buckets.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteBuckets()
    {
        $user = User::factory()->create();
        $bucket = Bucket::factory()->create();

        $response = $this->actingAs($user)->delete('buckets/'.$bucket->id);

        $response->assertStatus(403);
        $this->assertModelExists($bucket);
    }
}
