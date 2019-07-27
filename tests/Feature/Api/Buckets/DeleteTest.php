<?php

namespace Tests\Feature\Api\Buckets;

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
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($admin)->delete('api/buckets/'.$bucket->id, ['ACCEPT' => 'application/json']);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('buckets', [
            'id' => $bucket->id,
        ]);
    }
}
