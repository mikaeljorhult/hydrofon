<?php

namespace Tests\Browser\Categories;

use Hydrofon\User;
use Hydrofon\Category;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StoreTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Categories can be stored through create form.
     *
     * @return void
     */
    public function testCategoriesCanBeStored()
    {
        $user = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->make();

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/categories/create')
                    ->type('name', $category->name)
                    ->press('Create')
                    ->assertPathIs('/categories')
                    ->assertSee($category->name);
        });
    }

    /**
     * Requests with incomplete data is redirected back to create form.
     *
     * @return void
     */
    public function testInvalidCategoryIsRedirectedBackToCreateForm()
    {
        $user = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->make();

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/categories/create')
                    ->press('Create')
                    ->assertPathIs('/categories/create');
        });
    }
}
