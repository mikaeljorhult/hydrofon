<?php

namespace Tests\Browser\Categories;

use Hydrofon\Category;
use Hydrofon\User;
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
        $user = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/home')
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
        $user     = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/categories')
                    ->assertSee($category->name);
        });
    }
}
