<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Register;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    /** @test */
    public function it_can_see_the_register_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register());
        });
    }

    /**
     * @test
     */
    public function it_can_register_a_user()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new Register())
                ->type('name', 'Balwant')
                ->type('email', 'select2007@gmail.com')
                ->type('mobile', '1236547809')
                ->type('company', 'SportsFuel')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->press('Register')
                ->assertPathIs('/bigindia/backend/crm/products/create')
                ->assertSee('New Product');
        });
    }
}
