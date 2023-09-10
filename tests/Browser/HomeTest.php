<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomeTest extends DuskTestCase
{
    public function testHomePageIsAvailable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('HYDROFON')
                ->assertSeeLink('Login')
                ->assertSeeLink('Register');
        });
    }
}
