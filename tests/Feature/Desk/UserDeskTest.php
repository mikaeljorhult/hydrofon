<?php

namespace Tests\Feature\Desk;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDeskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regular users can't visit the desk.
     *
     * @return void
     */
    public function testUsersCantVisitDesk()
    {
        $response = $this->actingAs(factory(User::class)->create())->get('/desk');

        $response->assertStatus(403);
    }

    /**
     * Administrator users can visit the desk.
     *
     * @return void
     */
    public function testAdministratorsCanVisitDesk()
    {
        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk');

        $response->assertStatus(200);
    }

    /**
     * Users can be found by their e-mail address.
     *
     * @return void
     */
    public function testSearchRedirectsToUserPage()
    {
        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('/desk', [
                             'search' => 'search-term',
                         ]);

        $response->assertStatus(302);
        $response->assertRedirect('/desk/search-term');
    }

    /**
     * Users can be found by their e-mail address.
     *
     * @return void
     */
    public function testUsersCanBeFoundByEmail()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk/'.$user->email);

        $response->assertStatus(200);
        $response->assertSee(e($user->name));
    }

    /**
     * Users can be found by an identifier.
     *
     * @return void
     */
    public function testUsersCanBeFoundByIdentifier()
    {
        $user = factory(User::class)->create();
        $user->identifiers()->create(['value' => 'user-identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk/user-identifier');

        $response->assertStatus(200);
        $response->assertSee(e($user->name));
    }
}
