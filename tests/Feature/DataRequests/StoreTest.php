<?php

namespace Tests\Feature\DataRequests;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User personal data can be downloaded.
     *
     * @return void
     */
    public function testDataExportCanBeRequested()
    {
        $this->actingAs(factory(User::class)->create())->post('datarequests')
             ->assertStatus(200)
             ->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * User must be logged in to access data export..
     *
     * @return void
     */
    public function testUserMustBeLoggedInToRequestExport()
    {
        $this->post('datarequests')
             ->assertStatus(302);
    }
}
