<?php

namespace Tests\Feature\Api\Buckets;

use Hydrofon\Bucket;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buckets can be updated.
     *
     * @return void
     */
    public function testBucketsCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($admin)->put('api/buckets/'.$bucket->id, [
            'name'  => 'Updated Name',
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202)
                 ->assertJsonStructure([
                     'id',
                     'name',
                 ])
                 ->assertJsonFragment([
                     'id'    => $bucket->id,
                     'name'  => 'Updated Name',
                 ]);

        $this->assertDatabaseHas('buckets', [
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Buckets must have a name.
     *
     * @return void
     */
    public function testBucketsMustHaveName()
    {
        $admin = factory(User::class)->states('admin')->create();
        $bucket = factory(Bucket::class)->create();

        $response = $this->actingAs($admin)->put('api/buckets/'.$bucket->id, [
            'name'  => '',
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('buckets', [
            'name' => $bucket->name,
        ]);
    }
}
