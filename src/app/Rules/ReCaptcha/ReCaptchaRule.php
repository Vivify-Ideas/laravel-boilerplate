<?php

namespace App\Rules\ReCaptcha;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class ReCaptchaRule implements Rule {
    /**
     * @var Client
     */
    private $_client;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_client = new Client();
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
        $response = $this->_client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params'=> [
                    'secret' => config('recaptcha.secret'),
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
        return trans('messages.invalid_recaptcha');
    }
}
