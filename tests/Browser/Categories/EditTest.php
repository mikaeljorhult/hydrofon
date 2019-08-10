<?php

namespace Tests\Browser\Categories;

use Hydrofon\User;
use Hydrofon\Category;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from categories index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $user = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/categories')
                    ->clickLink($category->name)
                    ->assertPathIs('/categories/'.$category->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
