<?php

namespace Tests\Browser\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoriesCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCategoriesCreateIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories')
                ->assertSeeLink('New category')
                ->clickLink('New category')
                ->assertPathIs('/categories/create')
                ->assertSee('Create category')
                ->logout();
        });
    }

    public function testItemCanBeCreated(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories/create')
                ->assertSee('Create category')
                ->type('name', 'New category')
                ->clickAndWaitForReload('@submitcreate')
                ->assertPathIs('/categories')
                ->logout();
        });

        $this->assertDatabaseHas(Category::class, [
            'name' => 'New category',
        ]);
    }

    public function testCreateCanBeCancelled(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories/create')
                ->click('@submitcancel')
                ->assertPathIs('/categories')
                ->logout();
        });
    }
}
