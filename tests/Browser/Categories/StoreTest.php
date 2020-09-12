<?php

namespace Tests\Browser\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $user = User::factory()->admin()->create();
        $category = Category::factory()->make();

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
        $user = User::factory()->admin()->create();
        $category = Category::factory()->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/categories/create')
                    ->press('Create')
                    ->assertPathIs('/categories/create');
        });
    }
}
