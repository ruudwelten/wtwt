<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BrowserTest extends DuskTestCase
{
    public function testHome()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('WTWT')
                ->screenshot('home')
                ->click('.chevron')
                ->pause(1000)
                ->screenshot('home-scroll')
                ->assertSee('github.com/ruudwelten/wtwt')
                ->click('.link a')
                ->assertPathIs('/history/');
        });
    }

    public function testHistory()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/history')
                ->screenshot('history')
                ->assertSee('Afgelopen 24 uur')
                ->scrollIntoView('.footer')
                ->pause(1000)
                ->screenshot('history-scroll')
                ->assertSee('Afgelopen jaar')
                ->scrollIntoView('.link')
                ->pause(1000)
                ->click('.link a')
                ->assertPathIs('/');
        });
    }
}
