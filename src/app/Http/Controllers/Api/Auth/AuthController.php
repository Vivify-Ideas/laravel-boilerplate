<?php

namespace App\Http\Controllers\Api\Auth;

use App\Services\Auth\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserCreateRequest;

class AuthController extends Controller {
    private $_authService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->_authService = $authService;

        $this->middleware('auth:api', ['except' => [ 'login', 'register', 'refresh' ]]);
        $this->middleware('email-verified', ['except' => ['login', 'register']]);
    }

    /**
     * @SWG\Post(
     *   tags={"Auth"},
     *   path="/auth/login",
     *   summary="Login user",
     *   operationId="login",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="ex. user@test.com",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="[someUniqueValue]",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="g-recaptcha-response",
     *     in="formData",
     *     description="Google ReCaptcha Response",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->validated();

        return $this->_authService->login($credentials);
    }

    /**
     * @SWG\Post(
     *   tags={"Auth"},
     *   path="/auth/register",
     *   summary="Register new user",
     *   operationId="register",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="first_name",
     *     in="formData",
     *     description="ex. John Doe",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="last_name",
     *     in="formData",
     *     description="ex. John Doe",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="ex. user@test.com",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="[someUniqueValue]",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Parameter(
     *     name="g-recaptcha-response",
     *     in="formData",
     *     description="Google ReCaptcha Response",
     *     required=false,
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error")
     * )
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserCreateRequest $request)
    {
        $credentials = $request->validated();

        return $this->_authService->register($credentials);
    }

    /**
     * @SWG\Get(
     *   tags={"Auth"},
     *   path="/auth/me",
     *   summary="Get logged user",
     *   operationId="me",
     *   produces={"application/json"},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=422, description="Validation failed"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return auth()->user();
    }

    /**
     * @SWG\Post(
     *   tags={"Auth"},
     *   path="/auth/logout",
     *   summary="Invalidate JWT token",
     *   operationId="logout",
     *   produces={"application/json"},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     *
     * @param UserRefreshTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return ['message' => 'Successfully logged out'];
    }

    /**
     * @SWG\Post(
     *   tags={"Auth"},
     *   path="/auth/refresh",
     *   summary="Refresh JWT token",
     *   operationId="refresh",
     *   produces={"application/json"},
     *   @SWG\Response(response=200, description="Successful operation"),
     *   @SWG\Response(response=401, description="Unauthorized"),
     *   @SWG\Response(response=500, description="Internal server error"),
     *   security={{"authorization_token":{}}}
     * )
     *
     * @param UserRefreshTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->_authService->refresh();
    }
}
