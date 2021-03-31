<?php

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class Captcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = new Client();
        $secret = env('CAPTCHA_SECRET_KEY');
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify',
                    [
                        'form_params' => [
                            // 'secret' => env('GOOGLE_RECAPTCHA_SECRET', false),
                            'secret' =>  $secret,
                            'remoteip' => request()->getClientIp(),
                            'response' => $value
                        ]
                    ]
                );
        $body = json_decode((string)$response->getBody());
        return $body->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Complete the reCAPTCHA to submit the form';
    }
}
