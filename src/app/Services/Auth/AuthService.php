<?php

namespace App\Services\Auth;

use App\Models\User\User;
use App\Exceptions\UnauthorizedException;

class AuthService {
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    private function _respondWithToken($token) : array
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
    public function login(array $credentials) : array
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
    public function register($credentials) : array
    {
        User::create($credentials);

        return $this->login($credentials);
    }

    /**
     * Return refreshed JWT for authenticated user
     *
     * @return array
     */
    public function refresh() : array
    {
        $refreshedToken = auth()->refresh();

        return $this->_respondWithToken($refreshedToken);
    }
}
