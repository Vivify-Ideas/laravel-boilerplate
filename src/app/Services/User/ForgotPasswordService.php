<?php

namespace App\Services\User;

use App\Models\User\User;
use Carbon\Carbon;
use App\Mail\User\ForgotPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Constants\UserConstants;

class ForgotPasswordService {

    /**
     * Generates the forgot password token and send email
     * with token
     *
     * @param string $email
     * @return void
     */
    public function sendForgotPasswordToken(string $email) : void
    {
        $user = User::withEmail($email)->first();
        $user->forgot_password_token = Str::random(UserConstants::FORGOT_PASSWORD_LENGTH);
        $user->forgot_password_date = Carbon::now();
        $user->save();

        Mail::to($email)
            ->queue(
                new ForgotPasswordMail($user->forgot_password_token)
            );
    }

    /**
     * Find user by token and change password to the new one
     *
     * @param string $token
     * @param string $password
     * @return User
     */
    public function resetPassword(string $token, string $password) : User
    {
        $user = User::where('forgot_password_token', $token)->firstOrFail();
        $user->password = $password;
        $user->resetForgotPasswordToken();
        $user->save();

        return $user;
    }
}
