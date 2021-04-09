<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Login extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return 'login';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs('/bigindia/backend/crm/login')->assertSee('Sign in now')
            // ->assertSee('E-Mail Address')
            ->assertInputValue('email', '')
            // ->assertSee('Password')
            ->assertInputValue('password', '')
            ->assertNotChecked('remember')
            ->assertSee('Login')
            ->assertSee('Forgot password?')
            ->assertSee('New user, Register');
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
