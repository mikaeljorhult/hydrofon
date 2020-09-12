<?php

namespace Tests\Browser\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Categories can be updated through create form.
     *
     * @return void
     */
    public function testCategoriesCanBeUpdated()
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/categories/'.$category->id.'/edit')
                    ->type('name', 'New Category Name')
                    ->press('Update')
                    ->assertPathIs('/categories')
                    ->assertSee('New Category Name');
        });
    }

    /**
     * Categories can be updated through create form.
     *
     * @return void
     */
    public function testCategoriesMustHaveAName()
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $category) {
            $browser->loginAs($user)
                    ->visit('/categories/'.$category->id.'/edit')
                    ->type('name', '')
                    ->press('Update')
                    ->assertPathIs('/categories/'.$category->id.'/edit');
        });
    }
}
