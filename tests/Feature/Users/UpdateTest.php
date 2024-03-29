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
     */
    public function testEditRouteIsAvailable(): void
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
     */
    public function testUsersCanBeUpdated(): void
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
     */
    public function testUsersMustHaveAName(): void
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
     */
    public function testUsersMustHaveAnEmailAddress(): void
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
     */
    public function testEmailAddressMustBeUnique(): void
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
     */
    public function testPasswordMustBeConfirmed(): void
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
     */
    public function testPasswordCanBeChanged(): void
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
     */
    public function testNonAdminUsersCanNotUpdateUsers(): void
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
