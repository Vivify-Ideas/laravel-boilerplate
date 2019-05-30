<?php

namespace App\Services\Auth\Social;

use JWTAuth;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Support\Str;
use App\Models\User\User;
use App\Exceptions\Auth\EmailExistsException;

abstract class SocialAuthService {

    /**
     * Function that gets the token from provider
     * and call login or register user
     *
     * @param string $accessToken
     * @return void
     */
    abstract public function login(string $accessToken);

    /**
     * Returns the socialite provider
     *
     * @return string
     */
    abstract protected function loginType() : string;

    /**
     * Gets the user first and last name from SocialiteUser
     *
     * @param SocialiteUser $user
     * @return array
     */
    abstract protected function parseUserName(SocialiteUser $user) : array;


    /**
    * Register a user using their Google account details
    *
    * @param SocialiteUser $providedUser
    *
    * @return User
    */
    protected function _registerSocialUser(SocialiteUser $providedUser) : User
    {
        $email = $providedUser->getEmail();
        if (User::withEmail($email)->exists()) {
            throw new EmailExistsException;
        }

        list($firstName, $lastName) = array_values($this->parseUserName($providedUser));

        return $this->_registerUser(
            $email,
            $firstName,
            $lastName,
            $providedUser->getId(),
            $providedUser->getAvatar(),
            $this->loginType()
        );
    }

    /**
     * Register social user
     *
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $socialId
     * @param string $provider
     * @return User
     */
    protected function _registerUser(
        string $email,
        string $firstName,
        string $lastName,
        string $socialId,
        string $avatarUrl,
        string $provider
    ) : User {
        return User::create([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'social_id' => $socialId,
            'social_type' => $provider,
            'avatar' => $avatarUrl,
            'password' => Str::random(User::PASSWORD_LENGTH),
        ]);
    }

    /**
    * Return user's token
    *
    * @param User $user
    *
    * @return array
    */
    protected function _loginUserWithoutPassword(User $user) : array
    {
        return $this->_respondWithToken(
            JWTAuth::fromUser($user)
        );
    }

    /**
    * Create an array containing token info
    *
    * @param string $token
    *
    * @return array
    */
    private function _respondWithToken(string $token) : array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ];
    }
}
