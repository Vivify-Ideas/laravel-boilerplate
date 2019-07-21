<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ReCaptcha\ApplyReCaptchaRulesTrait;

class UserLoginRequest extends FormRequest {
    use ApplyReCaptchaRulesTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(
            $this->getCaptchaRules(),
            [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]
        );
    }
}
