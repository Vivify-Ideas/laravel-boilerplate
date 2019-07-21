<?php

namespace App\Rules\ReCaptcha;

use App\Rules\ReCaptcha\ReCaptchaRule;

trait ApplyReCaptchaRulesTrait {
    /**
     * Return validation for ReCaptcha
     * if it's enabled in configuration
     *
     * @return array
     */
    public function getCaptchaRules() : array
    {
        if (config('recaptcha.enabled')) {
            return [
                'g-recaptcha-response' => [
                    'required',
                    new ReCaptchaRule
                ]
            ];
        }

        return [];
    }
}
