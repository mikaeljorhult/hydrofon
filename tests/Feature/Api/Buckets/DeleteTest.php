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
        $bucket = factory(Bucket::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->deleteJson('api/buckets/'.$bucket->id)
             ->assertStatus(204);

        $this->assertDatabaseMissing('buckets', [
            'id' => $bucket->id,
        ]);
    }
}
