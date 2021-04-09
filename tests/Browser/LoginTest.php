<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Login;

class LoginTest extends DuskTestCase
{
    /**
     * for running this test just comment out the capcha validation in LoginController and same for Register as well
     * @test
     */
    public function it_can_login_a_user()
    {
        // $user = User::find('neeraj.tangariya123@gmail.com');
        $this->browse(function (Browser $browser) {
            $browser->visit(new Login)
                ->type('email', 'neeraj.tangariya321@gmail.com')
                ->type('password', 'Tang#@123')
                ->press('Login')
                ->assertDontSee('These credentials do not match our records.')
                ->assertSee('Products');
        });
    }
}
