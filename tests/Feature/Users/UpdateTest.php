<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Edit route is available.
     *
     * @return void
     */
    public function testEditRouteIsAvailable()
    {
        $user = User::factory()->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('users.edit', $user))
            ->assertSuccessful()
            ->assertSee($user->name);
    }

    /**
     * Users can be updated.
     *
     * @return void
     */
    public function testUsersCanBeUpdated()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email' => 'test@hydrofon.se',
            'name' => $user->name,
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'test@hydrofon.se',
        ]);
    }

    /**
     * Users must have a name.
     *
     * @return void
     */
    public function testUsersMustHaveAName()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email' => 'test@hydrofon.se',
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
        ]);
    }

    /**
     * Users must have an e-mail address.
     *
     * @return void
     */
    public function testUsersMustHaveAnEmailAddress()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email' => '',
            'name' => $user->name,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * E-mail address must be unique.
     *
     * @return void
     */
    public function testEmailAddressMustBeUnique()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email' => $admin->email,
            'name' => $user->name,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * Password can be provided but must be confirmed.
     *
     * @return void
     */
    public function testPasswordMustBeConfirmed()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email' => $admin->email,
            'name' => $user->name,
            'password' => 'password',
            'password_confirmation' => 'not-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseHas('users', [
            'password' => $user->password,
        ]);
    }

    /**
     * Password can be changed.
     *
     * @return void
     */
    public function testPasswordCanBeChanged()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email' => $user->email,
            'name' => $user->name,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $user->refresh();

        $response->assertRedirect();
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    /**
     * Non admin users can not update users..
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateUsers()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put('users/'.$user->id, [
            'email' => 'test@hydrofon.se',
            'name' => $user->name,
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => $user->email,
        ]);
    }
}
