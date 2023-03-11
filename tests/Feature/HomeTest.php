<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
