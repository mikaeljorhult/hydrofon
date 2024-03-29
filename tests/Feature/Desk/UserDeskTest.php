<?php

namespace Tests\Feature\Desk;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDeskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regular users can't visit the desk.
     */
    public function testUsersCantVisitDesk(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/desk');

        $response->assertStatus(403);
    }

    /**
     * Administrator users can visit the desk.
     */
    public function testAdministratorsCanVisitDesk(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk');

        $response->assertStatus(200);
    }

    /**
     * Users can be found by their e-mail address.
     */
    public function testSearchRedirectsToUserPage(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create())
            ->post('/desk', [
                'search' => 'search-term',
            ]);

        $response->assertStatus(302);
        $response->assertRedirect('/desk/search-term');
    }

    /**
     * Users can be found by their e-mail address.
     */
    public function testUsersCanBeFoundByEmail(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/'.$user->email);

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    /**
     * Users can be found by an identifier.
     */
    public function testUsersCanBeFoundByIdentifier(): void
    {
        $user = User::factory()->create();
        $user->identifiers()->create(['value' => 'user-identifier']);

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/user-identifier');

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }
}
