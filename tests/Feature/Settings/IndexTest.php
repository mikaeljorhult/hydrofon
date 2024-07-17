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
            ])
            ->assertRedirect('settings')
            ->assertSessionHasNoErrors();

        $this->assertEquals('all', app(General::class)->require_approval);
    }

    public function testInvalidValuesAreCaught(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from('settings')
            ->post('settings', [
                'require_approval' => 'invalid',
            ])
            ->assertRedirect('settings')
            ->assertSessionHasErrors(['require_approval']);

        $this->assertEquals('none', app(General::class)->require_approval);
    }
}
