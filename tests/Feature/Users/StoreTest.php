<?php

namespace Tests\Feature\Users;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Users can be created and stored.
     *
     * @return void
     */
    public function testUsersCanBeStored()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => $user->email,
            'name'                  => $user->name,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => $user->email,
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
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => $user->email,
            'name'                  => '',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertCount(1, User::all());
        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
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
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => '',
            'name'                  => $user->name,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertCount(1, User::all());
        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
        ]);
    }

    /**
     * E-mail address must be unique and not in use by another user.
     *
     * @return void
     */
    public function testEmailAddressMustBeUnique()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => $admin->email,
            'name'                  => $user->name,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertCount(1, User::all());
        $this->assertDatabaseMissing('users', [
            'name' => $user->name,
        ]);
    }

    /**
     * Users must have a password.
     *
     * @return void
     */
    public function testUsersMustHaveAPassword()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => $user->email,
            'name'                  => $user->name,
            'password'              => '',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
        $this->assertCount(1, User::all());
        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * Password must match confirmation.
     *
     * @return void
     */
    public function testPasswordMustBeConfirmed()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => $user->email,
            'name'                  => $user->name,
            'password'              => 'password',
            'password_confirmation' => 'not-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
        $this->assertCount(1, User::all());
        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }

    /**
     * Users can be created and stored.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreUsers()
    {
        $admin = User::factory()->create();
        $user = User::factory()->make();

        $response = $this->actingAs($admin)->post('users', [
            'email'                 => $user->email,
            'name'                  => $user->name,
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
        ]);
    }
}
