<?php
namespace App\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\User\User;

class SamePasswordRule implements Rule {

    private $_currentPassword;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $token)
    {
        $this->_currentPassword = User::where('forgot_password_token', $token)
          ->pluck('password')
          ->first();
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
        return !Hash::check($value, $this->_currentPassword);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your new password matches the old password';
    }
}
