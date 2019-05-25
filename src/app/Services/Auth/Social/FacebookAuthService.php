<?php

namespace App\Services\Auth\Social;

use App\Services\Auth\Social\SocialAuthService;
use App\Models\User\User;
use Laravel\Socialite\Two\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthService extends SocialAuthService {

    /**
     * Login user through facebook or create new account if it's missing
     *
     * @param string $accessToken
     * @return array
     */
    public function login(string $accessToken) : array
    {
        try {
            $providedUser = Socialite::driver(User::SOCIAL_FACEBOOK)->userFromToken($accessToken);
        } catch (Exception $e) {
            throw new FacebookAuthFailedException;
        }

         $user = User::withFacebookSocialId($providedUser->getId())->first()
            ?? $this->_registerSocialUser($providedUser);
         return $this->_loginUserWithoutPassword($user);
    }


    /**
     * Returns the socialite provider, in this case it's 'facebook'
     *
     * @return string
     */
    protected function loginType(): string
    {
        return User::SOCIAL_FACEBOOK;
    }

    /**
     * Parse user name and returns first and last name
     *
     * @param SocialiteUser $providedUser
     * @return array
     */
    protected function parseUserName(SocialiteUser $providedUser) : array
    {
        $names = explode(' ', $providedUser->getName(), 2);

        return [
            'first_name' => $names[0],
            'second_name' => $names[1]
        ];
    }
}
