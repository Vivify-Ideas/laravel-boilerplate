<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\User\MatchCurrentPasswordRule;

class UserChangePasswordRequest extends FormRequest {
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
            'current_password' => [
                'required',
                'string',
                'min:6',
                new MatchCurrentPasswordRule(auth()->user())
            ],
            'new_password' => 'required|confirmed|string|min:6'
        ];
    }
}
