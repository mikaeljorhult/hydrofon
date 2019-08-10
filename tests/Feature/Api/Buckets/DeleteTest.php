<?php

namespace Tests\Feature\Api\Buckets;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Bucket;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
