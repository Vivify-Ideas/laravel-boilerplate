<?php

namespace App\Services\Auth\Social;

use App\Services\Auth\Social\SocialAuthService;
use App\Models\User\User;
use Laravel\Socialite\Two\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService extends SocialAuthService {

    /**
    * Login or register using Google accessToken
    *
    * @param string $accessToken
    *
    * @return array an array containing bearer token
    */
    public function login(string $accessToken) : array
    {
        try {
            $providedUser = Socialite::driver(User::SOCIAL_GOOGLE)->userFromToken($accessToken);
        } catch (Exception $e) {
            throw new GoogleAuthFailedException;
        }

        $user = User::withGoogleSocialId($providedUser->getId())->first()
          ?? $this->_registerSocialUser($providedUser);

        return $this->_loginUserWithoutPassword($user);
    }

    /**
     * Returns the socialite provider, in this case it's 'google'
     *
     * @return string
     */
    protected function loginType(): string
    {
        return User::SOCIAL_GOOGLE;
    }

    /**
     * Parse user name and returns the first and last name
     *
     * @param SocialiteUser $providedUser
     * @return array
     */
    protected function parseUserName(SocialiteUser $providedUser) : array
    {
        return [
            'first_name' => $providedUser->getRaw()['given_name'],
            'second_name' => $providedUser->getRaw()['family_name']
        ];
    }
}
