<?php

namespace Tests\Feature\Api\Buckets;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Bucket;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $bucket = factory(Bucket::class)->make();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->postJson('api/buckets', $bucket->toArray())
             ->assertStatus(201)
             ->assertJsonStructure([
                 'id',
                 'name',
             ])
             ->assertJsonFragment([
                 'name' => $bucket->name,
             ]);

        $this->assertDatabaseHas('buckets', [
            'id'   => 1,
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
        $bucket = factory(Bucket::class)->make(['name' => null]);

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->postJson('api/buckets', $bucket->toArray())
             ->assertStatus(422)
             ->assertJsonValidationErrors('name');

        $this->assertEquals(0, \Hydrofon\Bucket::count());
    }
}
