<?php
namespace App\Rules\User;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User\User;
use Carbon\Carbon;

class ForgotPasswordTokenExistsRule implements Rule {

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $expiredTokenDate = Carbon::now()->subHours(24);
        $user = User::where('forgot_password_token', $value)
            ->whereDate('forgot_password_date', '>=', $expiredTokenDate)
            ->first();

        return !empty($user);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Token is invalid or expired';
    }
}
