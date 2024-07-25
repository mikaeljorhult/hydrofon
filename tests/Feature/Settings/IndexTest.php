<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use App\Settings\General;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testSettingsRouteIsAvailable(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('settings')
            ->assertSuccessful();
    }

    public function testOnlyAvailableToAdministrators(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('settings')
            ->assertForbidden();

        $this
            ->get('settings')
            ->assertForbidden();
    }

    public function testSettingsCanBeStored(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('settings', [
                'require_approval' => 'all',
                'desk_inclusion_earlier' => 15,
                'desk_inclusion_later' => 60,
                'prune_bookings' => 60,
                'prune_users' => 60,
            ])
            ->assertRedirect('settings')
            ->assertSessionHasNoErrors();

        $this->assertEquals('all', app(General::class)->require_approval);
        $this->assertEquals(15, app(General::class)->desk_inclusion_earlier);
        $this->assertEquals(60, app(General::class)->desk_inclusion_later);
        $this->assertEquals(60, app(General::class)->prune_bookings);
        $this->assertEquals(60, app(General::class)->prune_users);
    }

    public function testInvalidValuesAreCaught(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from('settings')
            ->post('settings', [
                'require_approval' => 'invalid',
                'desk_inclusion_earlier' => -1,
                'desk_inclusion_later' => 241,
                'prune_bookings' => -1,
                'prune_users' => 10000,
            ])
            ->assertRedirect('settings')
            ->assertSessionHasErrors([
                'require_approval',
                'desk_inclusion_earlier',
                'desk_inclusion_later',
                'prune_bookings',
                'prune_users',
            ]);

        $this->assertEquals('none', app(General::class)->require_approval);
        $this->assertEquals(0, app(General::class)->desk_inclusion_earlier);
        $this->assertEquals(0, app(General::class)->desk_inclusion_later);
        $this->assertEquals(180, app(General::class)->prune_bookings);
        $this->assertEquals(365, app(General::class)->prune_users);
    }
}
