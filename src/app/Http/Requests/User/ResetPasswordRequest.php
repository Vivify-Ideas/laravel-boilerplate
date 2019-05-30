<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\User\ForgotPasswordTokenExistsRule;
use App\Rules\User\SamePasswordRule;

class ResetPasswordRequest extends FormRequest {
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
        return [
            'token' => [
                'required',
                'string',
                new ForgotPasswordTokenExistsRule()
            ],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:6',
                new SamePasswordRule($this->get('token'))
            ]
        ];
    }
}
