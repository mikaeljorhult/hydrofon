<?php

namespace Tests\Feature\Api\Buckets;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Bucket;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $bucket = factory(Bucket::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/buckets/'.$bucket->id, [
                 'name' => 'Updated Name',
             ])
             ->assertStatus(202)
             ->assertJsonStructure([
                 'id',
                 'name',
             ])
             ->assertJsonFragment([
                 'id'   => $bucket->id,
                 'name' => 'Updated Name',
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
        $bucket = factory(Bucket::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/buckets/'.$bucket->id, [
                 'name' => '',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('buckets', [
            'name' => $bucket->name,
        ]);
    }
}
