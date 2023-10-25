<?php

namespace Tests\Browser\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoriesEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testEditRouteIsReachable(): void
    {
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($category) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories')
                ->assertSeeLink($category->name)
                ->clickLink($category->name)
                ->assertPathIs('/categories/'.$category->id.'/edit')
                ->assertSee('Edit category')
                ->logout();
        });
    }

    public function testItemCanBeEdited(): void
    {
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($category) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories/'.$category->id.'/edit')
                ->assertSee('Edit category')
                ->type('name', 'New name')
                ->clickAndWaitForReload('@submitupdate')
                ->assertPathIs('/categories')
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Category::class, [
            'id' => $category->id,
            'name' => 'New name',
        ]);
    }

    public function testEditCanBeCancelled(): void
    {
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($category) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories/'.$category->id.'/edit')
                ->click('@submitcancel')
                ->assertPathIs('/categories')
                ->logout();
        });
    }
}
