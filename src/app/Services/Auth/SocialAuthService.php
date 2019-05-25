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

class SocialAuthService {

    /**
    * Login or register using Google accessToken
    *
    * @param string $accessToken
    *
    * @return array an array containing bearer token
    */
    public function loginOrRegisterViaGoogle(string $accessToken, string $provider) : array
    {
        try {
            $providedUser = Socialite::driver($provider)->userFromToken($accessToken);
        } catch (Exception $e) {
            throw new GoogleAuthFailedException;
        }

        $user = User::where('social_id', $providedUser->getId())->first()
          ?? $this->_registerGoogleUser($providedUser, $provider);

        return $this->_loginUserWithoutPassword($user);
    }

    /**
    * Register a user using their Google account details
    *
    * @param SocialiteUser $providedUser
    *
    * @return User
    */
    private function _registerGoogleUser(SocialiteUser $providedUser, string $provider) : User
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
            $provider
        );
    }
    private function _registerUser(
        string $email,
        string $firstName,
        string $lastName,
        string $googleId,
        string $provider
    ) : User {
        return User::create([
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'social_id' => $googleId,
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
