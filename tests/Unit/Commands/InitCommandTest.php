<?php

namespace Tests\Unit\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InitCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin user is created during init.
     *
     * @return void
     */
    public function testUserIsCreated()
    {
        $this
            ->artisan('hydrofon:init')
            ->expectsQuestion('Name', 'New User')
            ->expectsQuestion('E-mail Address', 'default@hydrofon.se')
            ->expectsQuestion('Password', 'password')
            ->expectsOutput('User was created successfully!')
            ->assertSuccessful();

        $this->assertDatabaseHas(User::class, [
            'name' => 'New User',
            'email' => 'default@hydrofon.se',
        ]);
    }

    /**
     * E-mail address is required.
     *
     * @return void
     */
    public function testEmailAddressIsRequired()
    {
        $this
            ->artisan('hydrofon:init')
            ->expectsQuestion('Name', 'New User')
            ->expectsQuestion('E-mail Address', '')
            ->expectsQuestion('Password', 'password')
            ->expectsOutput('Could not create user!')
            ->assertFailed();

        $this->assertDatabaseCount(User::class, 0);
    }

    /**
     * E-mail address is required.
     *
     * @return void
     */
    public function testEmailAddressMustBeUnique()
    {
        $user = User::factory()->create();

        $this
            ->artisan('hydrofon:init')
            ->expectsQuestion('Name', 'New User')
            ->expectsQuestion('E-mail Address', $user->email)
            ->expectsQuestion('Password', 'password')
            ->expectsOutput('Could not create user!')
            ->assertFailed();

        $this->assertDatabaseCount(User::class, 1);
    }

    /**
     * Password is required.
     *
     * @return void
     */
    public function testPasswordIsRequired()
    {
        $this
            ->artisan('hydrofon:init')
            ->expectsQuestion('Name', 'New User')
            ->expectsQuestion('E-mail Address', 'default@hydrofon.se')
            ->expectsQuestion('Password', '')
            ->expectsOutput('Could not create user!')
            ->assertFailed();

        $this->assertDatabaseCount(User::class, 0);
    }

    /**
     * User information can be passed as options.
     *
     * @return void
     */
    public function testInformationCanBePassedAsOptions()
    {
        $this
            ->artisan('hydrofon:init', [
                '--name' => 'New User',
                '--email' => 'default@hydrofon.se',
                '--password' => 'password',
            ])
            ->expectsOutput('User was created successfully!')
            ->assertSuccessful();

        $this->assertDatabaseHas(User::class, [
            'name' => 'New User',
            'email' => 'default@hydrofon.se',
        ]);
    }

    /**
     * User information can be passed as options.
     *
     * @return void
     */
    public function testMissingOptionsAskForInformation()
    {
        $this
            ->artisan('hydrofon:init', [
                '--name' => 'New User',
                '--password' => 'password',
            ])
            ->expectsQuestion('E-mail Address', 'default@hydrofon.se')
            ->expectsOutput('User was created successfully!')
            ->assertSuccessful();

        $this->assertDatabaseHas(User::class, [
            'name' => 'New User',
            'email' => 'default@hydrofon.se',
        ]);
    }
}
