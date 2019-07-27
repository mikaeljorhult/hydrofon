<?php

namespace Tests\Feature\Api\Buckets;

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
        $bucket = factory(Bucket::class)->make();

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/buckets', $bucket->toArray(), ['ACCEPT' => 'application/json']);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'name',
                 ])
                 ->assertJsonFragment([
                     'name'  => $bucket->name,
                 ]);

        $this->assertDatabaseHas('buckets', [
            'id'    => 1,
            'name'  => $bucket->name,
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

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/buckets', $bucket->toArray(), ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');

        $this->assertEquals(0, \Hydrofon\Bucket::count());
    }
}
