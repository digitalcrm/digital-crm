<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Register extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return 'register';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs('/bigindia/backend/crm/register')
            ->assertSee('Register Now')
            ->assertInputValue('name', '')
            ->assertInputValue('email', '')
            ->assertInputValue('mobile', '')
            ->assertInputValue('company', '')
            ->assertInputValue('password', '')
            ->assertInputValue('password_confirmation', '')
            ->assertSee('Register')
            ->assertDontSee('email is already exists.')
            ->assertDontSee('The mobile must be 10 digits.')
            ->assertDontSee('The password confirmation does not match.');
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
