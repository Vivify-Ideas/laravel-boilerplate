<?php

namespace App\Services\Auth;

use JWTAuth;
use Exception;
use Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Support\Str;
use App\Models\User\User;
use App\Exceptions\Auth\EmailExistsException;
use App\Exceptions\Auth\GoogleAuthFailedException;
use App\Exceptions\Auth\FacebookAuthFailedException;

class SocialAuthService {

    /**
    * Login or register using Google accessToken
    *
    * @param string $accessToken
    *
    * @return array an array containing bearer token
    */
    public function loginOrRegisterViaGoogle(string $accessToken) : array
    {
        try {
            $providedUser = Socialite::driver(User::SOCIAL_GOOGLE)->userFromToken($accessToken);
        } catch (Exception $e) {
            throw new GoogleAuthFailedException;
        }

        $user = User::withGoogleSocialId($providedUser->getId())->first()
          ?? $this->_registerGoogleUser($providedUser);

        return $this->_loginUserWithoutPassword($user);
    }

    /**
     * Login user through facebook or create new account if it's missing
     *
     * @param string $accessToken
     * @return array
     */
    public function loginOrRegisterViaFacebook(string $accessToken) : array
    {
        try {
            $providedUser = Socialite::driver(User::SOCIAL_FACEBOOK)->userFromToken($accessToken);
        } catch (Exception $e) {
            throw new FacebookAuthFailedException;
        }

         $user = User::withFacebookSocialId($providedUser->getId())->first()
            ?? $this->_registerFacebookUser($providedUser);
         return $this->_loginUserWithoutPassword($user);
    }

    /**
    * Register a user using their Google account details
    *
    * @param SocialiteUser $providedUser
    *
    * @return User
    */
    private function _registerGoogleUser(SocialiteUser $providedUser) : User
    {
        $email = $providedUser->getEmail();
        if (User::withEmail($email)->exists()) {
            throw new EmailExistsException;
        }

        return $this->_registerUser(
            $email,
            $providedUser->getRaw()['given_name'],
            $providedUser->getRaw()['family_name'],
            $providedUser->getId(),
            User::SOCIAL_GOOGLE
        );
    }

    /**
     * Register a user using their Facebook account details
     *
     * @param SocialiteUser $providedUser
     *
     * @return User
     */
    private function _registerFacebookUser(SocialiteUser $providedUser) : User
    {
        $email = $providedUser->getEmail();
        if (User::withEmail($email)->exists()) {
            throw new EmailExistsException;
        }
        $name_array = explode(' ', $providedUser->getName(), 2);

         return $this->_registerUser(
             $email,
             $name_array[0],
             $name_array[1],
             $providedUser->getId(),
             User::SOCIAL_FACEBOOK
         );
    }
    private function _registerUser(
        string $email,
        string $firstName,
        string $lastName,
        string $socialId,
        string $provider
    ) : User {
        return User::create([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'social_id' => $socialId,
            'provider' => $provider,
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
    private function _loginUserWithoutPassword(User $user) : array
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
