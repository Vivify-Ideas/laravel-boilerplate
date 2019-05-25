<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SocialSignInRequest;
use App\Services\Auth\Social\FacebookAuthService;
use App\Services\Auth\Social\GoogleAuthService;

class SocialAuthController extends Controller {
    private $_facebookAuthService;
    private $_googleAuthService;

    public function __construct(
        FacebookAuthService $facebookAuthService,
        GoogleAuthService $googleAuthService
    ) {
        $this->_facebookAuthService = $facebookAuthService;
        $this->_googleAuthService = $googleAuthService;
    }

    /**
     * Login or register google account
     *
     * @param SocialSignInRequest $request
     * @return array
     */
    public function handleGoogleLogin(SocialSignInRequest $request): array
    {
        return $this->_googleAuthService->login(
            $request->get('accessToken')
        );
    }

    /**
     * Login or register with facebook account
     *
     * @param SocialSignInRequest $request
     * @return array
     */
    public function handleFacebookLogin(SocialSignInRequest $request): array
    {
        return $this->_facebookAuthService->login(
            $request->get('accessToken')
        );
    }
}
