<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
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
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/auth/login",
     *   summary="Login user",
     *   operationId="login",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="g-recaptcha-response", type="string", example="something"),
     *     ),
     *   ),
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent())
     * )
     *
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        return response()->json($this->_authService->login($credentials));
    }

    /**
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/auth/register",
     *   summary="Register new user",
     *   operationId="register",
     *   @OA\RequestBody(
     *     required=true,
     *     description="Pass user credentials",
     *     @OA\JsonContent(
     *       required={"first_name", "last_name", "email","password"},
     *       @OA\Property(property="first_name", type="string", example="ex. John"),
     *       @OA\Property(property="last_name", type="string", example="ex. Doe"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="g-recaptcha-response", type="string", example="something"),
     *     ),
     *   ),
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent())
     * )
     * @param UserCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserCreateRequest $request)
    {
        $credentials = $request->validated();

        return response()->json($this->_authService->register($credentials));
    }

    /**
     * @OA\Get(
     *   tags={"Auth"},
     *   path="/auth/me",
     *   summary="Get logged user",
     *   operationId="me",
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=422, description="Validation failed", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent()),
     *   security={{"authorization_token":{}}}
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/auth/logout",
     *   summary="Invalidate JWT token",
     *   operationId="logout",
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent()),
     *   security={{"authorization_token":{}}}
     * )
     *
     * @param UserRefreshTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Post(
     *   tags={"Auth"},
     *   path="/auth/refresh",
     *   summary="Refresh JWT token",
     *   operationId="refresh",
     *   @OA\Response(response=200, description="Successful operation", @OA\JsonContent()),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent()),
     *   @OA\Response(response=500, description="Internal server error", @OA\JsonContent()),
     *   security={{"authorization_token":{}}}
     * )
     *
     * @param UserRefreshTokenRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json($this->_authService->refresh());
    }
}
