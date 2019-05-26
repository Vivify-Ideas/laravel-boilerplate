<?php

namespace App\Services\User;

use App\Models\User\User;

class UserService {

    /**
     * Change the password on the user that is passed
     * to the method
     *
     * @param User $user
     * @param string $newPassword
     * @return User
     */
    public function changePassword(User $user, string $newPassword) : User
    {
        $user->password = $newPassword;
        $user->save();

        return $user;
    }
}
