<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function testProfileCanBeUpdated(): void
    {
        $this
            ->put('profile', [
                'name' => 'Test Name',
            ])
            ->assertRedirect('login');

        $this
            ->actingAs($user = User::factory()->create())
            ->put('profile', [
                'name' => 'Test Name',
            ])
            ->assertRedirect('profile');

        $this->assertDatabaseHas($user, ['name' => 'Test Name']);
    }

    public function testNameIsRequired(): void
    {
        $this
            ->actingAs($user = User::factory()->create(['name' => 'Old Name']))
            ->from('profile')
            ->put('profile', [
                'name' => '',
            ])
            ->assertSessionHasErrors('name')
            ->assertRedirect('profile');

        $this->assertDatabaseHas($user, ['name' => 'Old Name']);
    }
}
