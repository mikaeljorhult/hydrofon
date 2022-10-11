<?php

namespace Tests\Feature\Impersonation;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * An administration can see impersonation form.
     *
     * @return void
     */
    public function testAdministratorCanSeeImpersonationForm()
    {
        User::factory()->create();
        $response = $this->actingAs(User::factory()->admin()->create())->get('users');

        $response->assertStatus(200);
        $response->assertSee('impersonation');
    }

    /**
     * An administration can impersonate a user.
     *
     * @return void
     */
    public function testAdministratorCanImpersonateUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post('/impersonation', [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('calendar');
        $response->assertSessionHas('impersonate', $user->id);
        $response->assertSessionHas('impersonated_by', $admin->id);
    }

    /**
     * An administration can impersonate a user.
     *
     * @return void
     */
    public function testAdministratorCanStopImpersonatingUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)->post('/impersonation', [
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)->delete('/impersonation');

        $response->assertRedirect('/');
        $response->assertSessionMissing('impersonate');
    }

    /**
     * ID of the user to impersonate is required.
     *
     * @return void
     */
    public function testUserIDIsRequired()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/impersonation', [
            'user_id' => '',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionMissing('impersonate');
        $response->assertSessionHasErrors('user_id');
    }

    /**
     * User to impersonate must exist.
     *
     * @return void
     */
    public function testUserMustExist()
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/impersonation', [
            'user_id' => 100,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionMissing('impersonate');
        $response->assertSessionHasErrors('user_id');
    }

    /**
     * A user can not see impersonation form.
     *
     * @return void
     */
    public function testUserCanNotSeeImpersonationForm()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('calendar');

        $response->assertStatus(200);
        $response->assertDontSee('impersonation');
    }

    /**
     * A user can not impersonate another user.
     *
     * @return void
     */
    public function testUserCanNotImpersonateUser()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user1)->post('/impersonation', [
            'user_id' => $user2->id,
        ]);

        $response->assertStatus(403);
        $response->assertSessionMissing('impersonate');
    }
}
