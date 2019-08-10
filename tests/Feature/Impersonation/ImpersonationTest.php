<?php

namespace Tests\Feature\Impersonation;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $admin = factory(User::class)->states('admin')->create();
        $response = $this->actingAs($admin)->get('/');

        $response->assertStatus(200);
        $response->assertSee('topbar-impersonation');
    }

    /**
     * An administration can impersonate a user.
     *
     * @return void
     */
    public function testAdministratorCanImpersonateUser()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->post('/impersonation', [
            'user_id' => $user->id,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('impersonate', $user->id);
    }

    /**
     * An administration can impersonate a user.
     *
     * @return void
     */
    public function testAdministratorCanStopImpersonatingUser()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();

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
        $admin = factory(User::class)->states('admin')->create();

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
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('topbar-impersonation');
    }

    /**
     * A user can not impersonate another user.
     *
     * @return void
     */
    public function testUserCanNotImpersonateUser()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $response = $this->actingAs($user1)->post('/impersonation', [
            'user_id' => $user2->id,
        ]);

        $response->assertStatus(403);
        $response->assertSessionMissing('impersonate');
    }
}
