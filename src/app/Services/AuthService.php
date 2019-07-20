<?php

namespace App\Services;

use App\Models\User\User;
use App\Exceptions\UnauthorizedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use App\Exceptions\TokenExpiredException;

class AuthService {
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    private function _respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param array $credentials
     *
     * @return array
     */
    public function login($credentials)
    {
        if (!$token=auth()->attempt($credentials)) {
            throw new UnauthorizedException;
        }

        return $this->_respondWithToken($token);
    }

    /**
     * Create User resource and get a JWT via given credentials.
     *
     * @param array $credentials
     *
     * @return array
     */
    public function register($credentials)
    {
        User::create($credentials);

        return $this->login($credentials);
    }

    /**
     * Return refreshed JWT for authenticated user
     *
     * @return array
     */
    public function refresh()
    {
        $token = JWTAuth::getToken();

        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (TokenBlacklistedException $exception) {
            throw new TokenExpiredException();
        }

        return $this->_respondWithToken($refreshedToken);
    }
}
