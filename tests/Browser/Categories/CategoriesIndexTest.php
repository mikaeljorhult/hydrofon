<?php

namespace Tests\Browser\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoriesIndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testCategoriesIndexIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/calendar')
                ->assertSeeLink('Categories')
                ->clickLink('Categories')
                ->assertPathIs('/categories')
                ->assertSee('Categories')
                ->logout();
        });
    }

    public function testNameIsDisplayedInListing(): void
    {
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($category) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories')
                ->assertSee($category->name)
                ->logout();
        });
    }

    public function testItemCanBeEditedInline(): void
    {
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($category) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories')
                ->mouseover('@item-'.$category->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$category->id)
                ->type('name', 'New name')
                ->pause(500)
                ->press('Save')
                ->waitFor('@item-'.$category->id)
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Category::class, [
            'id' => $category->id,
            'name' => 'New name',
        ]);
    }

    public function testInlineEditingCanBeCancelled(): void
    {
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($category) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories')
                ->mouseover('@item-'.$category->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$category->id)
                ->press('Cancel')
                ->waitFor('@item-'.$category->id)
                ->assertSee($category->name)
                ->logout();
        });
    }

    public function testItemCanBeDeleted(): void
    {
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($category) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/categories')
                ->mouseover('@item-'.$category->id)
                ->click('@delete')
                ->waitUntilMissing('@item-'.$category->id)
                ->assertDontSee($category->name)
                ->logout();
        });
    }

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
}
