<?php

namespace Tests\Feature\DataRequests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * User personal data can be downloaded.
     */
    public function testDataExportCanBeRequested(): void
    {
        $this->actingAs(User::factory()->create())->post('datarequests')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * User must be logged in to access data export..
     */
    public function testUserMustBeLoggedInToRequestExport(): void
    {
        $this->post('datarequests')
            ->assertStatus(302);
    }
}
