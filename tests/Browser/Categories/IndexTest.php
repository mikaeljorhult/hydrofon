<?php

namespace Tests\Browser\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to index page from home.
     *
     * @return void
     */
    public function testUserCanNavigateToIndexPage()
    {
        $user = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->clickLink('Categories')
                    ->assertPathIs('/categories');
        });
    }

    /**
     * Available categories are displayed on index page.
     *
     * @return void
     */
    public function testCategoryIsListedOnIndexPage()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/categories')
                    ->assertSee($category->name);
        });
    }
}
