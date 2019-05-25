<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAuthService;
use App\Http\Requests\Auth\SocialSignInRequest;

class SocialAuthController extends Controller {
    private $_socialAuthService;

    public function __construct(SocialAuthService $socialAuthService)
    {
        $this->_socialAuthService = $socialAuthService;
    }

    /**
     * Login or register google account
     *
     * @param SocialSignInRequest $request
     * @return array
     */
    public function handleGoogleLogin(SocialSignInRequest $request): array
    {
        return $this->_socialAuthService->loginOrRegisterViaGoogle(
            $request->get('accessToken'),
            'google'
        );
    }
}
