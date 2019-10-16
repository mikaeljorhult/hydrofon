<?php

namespace Tests\Feature\Api\Buckets;

use Hydrofon\Bucket;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buckets are listed in index.
     *
     * @return void
     */
    public function testBucketsAreListed()
    {
        $bucket = factory(Bucket::class)->create();

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/buckets')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'name',
                     ],
                 ],
             ])
             ->assertJsonFragment([
                 'id'   => $bucket->id,
                 'name' => $bucket->name,
             ]);
    }

    /**
     * Buckets can be filtered by name.
     *
     * @return void
     */
    public function testBucketsAreFilteredByName()
    {
        $excludedBucket = factory(Bucket::class)->create(['name' => 'Excluded Bucket']);
        $includedBucket = factory(Bucket::class)->create(['name' => 'Included Bucket']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/buckets?filter[name]=included')
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $includedBucket->id,
             ])
             ->assertJsonMissing([
                 'id' => $excludedBucket->id,
             ]);
    }
}
